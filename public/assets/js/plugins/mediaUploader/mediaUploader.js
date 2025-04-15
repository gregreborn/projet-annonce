(function() {
    // Constantes
    const MAX_FILE_SIZE = 15 * 1024 * 1024; // 15 MB
    const CHUNK_SIZE = 15 * 1024 * 1024; // Taille d'un chunk (15 MB)
    
    // Helper function to ensure a file name includes an extension.
    function getFinalFileName(file) {
      // If the file name doesn't have a period, append a default extension.
      if (file.name.lastIndexOf('.') === -1) {
        if (file.type === 'video/mp4') {
          return file.name + '.mp4';
        } else if (file.type === 'video/webm') {
          return file.name + '.webm';
        } else if (file.type === 'video/ogg') {
          return file.name + '.ogg';
        } else if (file.type === 'application/pdf') {
          return file.name + '.pdf';
        } else if (file.type === 'application/msword') {
          return file.name + '.doc';
        } else if (file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
          return file.name + '.docx';
        }
        // Default fallback, you can choose what makes sense.
        return file.name + '.mp4';
      }
      return file.name;
    }
    // Tableau pour stocker les fichiers à uploader et leur état
    let filesToUpload = [];
    
    // Endpoint par défaut, qui peut être surchargé via les options d'initialisation
    let uploadEndpoint = 'assets/js/plugins/mediaUploader/media_endpoint.php';
    
    // Injecte la modale dans le DOM si elle n'existe pas déjà
    function injectHTML() {
      if (!document.getElementById("media-uploader-modal")) {
        const modalHTML = `
        <div id="media-uploader-modal" class="reveal-modal" data-reveal aria-hidden="true" role="dialog" style="max-width:800px;">
          <div class="modal" style="position:relative; padding:20px;">
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
            <h3>Télécharger des médias (vidéo/doc/pdf)</h3>
            <!-- Bouton visible pour sélectionner un fichier -->
            <button id="select-media" class="button">Sélectionner un fichier</button>
            <!-- Input de type file masqué -->
            <input type="file" id="media-uploader-input" multiple accept="video/mp4,video/webm,video/ogg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" style="display:none;">
            <!-- Zone d'affichage des tuiles pour chaque fichier sélectionné -->
            <div id="media-tile-container" style="display:flex; flex-wrap: wrap; gap:10px; margin-top:15px;"></div>
            <div id="button-group" style="text-align: right; margin-top: 15px;">
              <button id="upload-media" class="button success">Télécharger</button>
              <button id="close-media-uploader" class="button alert">Fermer</button>
            </div>
          </div>
        </div>
        <div class="reveal-modal-bg"></div>
        `;
        let container = document.createElement("div");
        container.innerHTML = modalHTML;
        document.body.appendChild(container);
        $(document).foundation(); // Initialisation de Foundation pour la modale
      }
    }
    
    // Génère un identifiant unique pour un fichier
    function generateFileId() {
      return 'file_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
    }
    
    function createFileTile(fileObj) {
        let tileContainer = document.getElementById("media-tile-container");
        if (!tileContainer) return;
        
        // Choix d'une icône selon le type de fichier avec Material Icons
        let iconHTML;
        if (fileObj.file.type.startsWith("video/")) {
          iconHTML = '<i class="material-icons" style="font-size:48px;">videocam</i>';
        } else if (fileObj.file.type === "application/pdf") {
          iconHTML = '<i class="material-icons" style="font-size:48px;">picture_as_pdf</i>';
        } else {
          // Pour les documents (doc, docx)
          iconHTML = '<i class="material-icons" style="font-size:48px;">description</i>';
        }
        
        // Crée la tuile
        let tile = document.createElement("div");
        tile.className = "media-tile";
        tile.setAttribute("data-fileid", fileObj.fileId);
        tile.style.position = "relative";
        tile.style.width = "150px";
        tile.style.border = "1px solid #ccc";
        tile.style.padding = "5px";
        tile.style.borderRadius = "5px";
        
        // Contenu de la tuile
        tile.innerHTML = `
          <div class="media-thumb" style="text-align:center;">
            ${iconHTML}
          </div>
          <div class="media-info" style="font-size:12px; margin-top:5px;">
            <span class="media-name">${fileObj.file.name}</span>
            <div class="media-progress-container" style="width:100%; background:#ddd; height:8px; border-radius:4px; margin-top:5px;">
              <div class="media-progress-bar" style="width:0%; background:#4caf50; height:100%; border-radius:4px;"></div>
            </div>
            <span class="media-progress-text" style="display:block; text-align:center;">0%</span>
          </div>
            <button class="remove-media">&times;</button>
        `;
        
        // Ajoute l'événement de suppression
        tile.querySelector(".remove-media").addEventListener("click", function() {
            deleteUploadedMedia(fileObj.fileId).then(function(resp) {
              removeFileTile(fileObj.fileId);
            }).catch(function(err) {
              console.error("Erreur lors de la suppression :", err);
              alert("Erreur lors de la suppression du fichier. Veuillez réessayer.");
            });
          });
          
        
        tileContainer.appendChild(tile);
    }
      
    // Supprime la tuile et le fichier correspondant du tableau
    function removeFileTile(fileId) {
      // Supprimer de filesToUpload
      filesToUpload = filesToUpload.filter(f => f.fileId !== fileId);
      // Supprimer la tuile du DOM
      let tile = document.querySelector(`.media-tile[data-fileid="${fileId}"]`);
      if (tile && tile.parentNode) {
        tile.parentNode.removeChild(tile);
      }
    }
    
    // Met à jour la barre de progression d'une tuile
    function updateTileProgress(fileId, percent) {
      let tile = document.querySelector(`.media-tile[data-fileid="${fileId}"]`);
      if (tile) {
        let progressBar = tile.querySelector(".media-progress-bar");
        let progressText = tile.querySelector(".media-progress-text");
        if (progressBar && progressText) {
          progressBar.style.width = percent + "%";
          progressText.textContent = percent + "%";
        }
      }
      console.log('Upload', fileId, ':', percent + '%');
    }
    
    // Gère la sélection de fichiers depuis l'input masqué
    function handleFiles(files) {
      Array.from(files).forEach(file => {
        console.log("File selected:", file.name, "Size:", file.size);
        const allowedTypes = [
          'video/mp4', 'video/webm', 'video/ogg',
          'application/pdf',
          'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        if (!allowedTypes.includes(file.type)) {
          console.error('Type de fichier non autorisé:', file.type);
          return;
        }
        
        let fileObj = {
          file: file,
          fileId: generateFileId(),
          totalChunks: Math.ceil(file.size / CHUNK_SIZE),
          uploadedChunks: 0,
          progress: 0
        };
        console.log("Calculated total chunks for", file.name, ":", fileObj.totalChunks);
        
        filesToUpload.push(fileObj);
        createFileTile(fileObj);
      });
    }
    
    
    // Upload d'un fichier, en découpant en chunks si nécessaire
    function uploadFile(fileObj) {
      console.log("Uploading file", fileObj.file.name, "Size:", fileObj.file.size, "Total chunks:", fileObj.totalChunks);
      if (fileObj.file.size <= CHUNK_SIZE) {
        uploadChunk(fileObj.file, fileObj.fileId, 0, 1)
          .then(response => {
            console.log('Fichier uploadé:', response);
            updateTileProgress(fileObj.fileId, 100);
          })
          .catch(error => {
            console.error('Erreur lors de l’upload du fichier:', error);
          });
      } else {
        let totalChunks = fileObj.totalChunks;
        let promises = [];
        for (let i = 0; i < totalChunks; i++) {
          let start = i * CHUNK_SIZE;
          let end = Math.min(fileObj.file.size, start + CHUNK_SIZE);
          let chunk = fileObj.file.slice(start, end);
          console.log("Chunk", i, "size:", chunk.size);
          let promise = uploadChunk(chunk, fileObj.fileId, i, totalChunks)
            .then(response => {
              fileObj.uploadedChunks++;
              let percent = Math.floor((fileObj.uploadedChunks / totalChunks) * 100);
              updateTileProgress(fileObj.fileId, percent);
              return response;
            });
          promises.push(promise);
        }
        Promise.all(promises)
          .then(results => {
            console.log('Tous les chunks ont été uploadés pour ', fileObj.fileId);
            // Automatically finalize the upload for this file, using getFinalFileName.
            finalizeUpload(fileObj.fileId, getFinalFileName(fileObj.file))
              .then(finalResponse => {
                console.log('Fichier final réassemblé:', finalResponse);
                updateTileProgress(fileObj.fileId, 100);
              })
              .catch(err => console.error('Erreur lors de la finalisation:', err));
          })
          .catch(err => console.error('Erreur lors de l’upload d’un chunk:', err));
      }
    }

    
    
    function finalizeAllUploads() {
      filesToUpload.forEach(fileObj => {
        // Lance la finalisation uniquement si tous les chunks ont été uploadés et la finalisation n'a pas encore été faite
        if (fileObj.uploadedChunks === fileObj.totalChunks) {
          finalizeUpload(fileObj.fileId)
            .then(finalResponse => {
              console.log('Fichier final réassemblé:', finalResponse);
              updateTileProgress(fileObj.fileId, 100);
              // Vous pouvez enregistrer finalResponse dans un champ caché du formulaire, par exemple
            })
            .catch(err => console.error('Erreur lors de la finalisation:', err));
        }
      });
    }
    
   // Upload d'un chunk via AJAX
   function uploadChunk(chunk, fileId, chunkIndex, totalChunks) {
    return new Promise(function(resolve, reject) {
      let formData = new FormData();
      formData.append('file', chunk);
      formData.append('fileId', fileId);
      formData.append('chunkIndex', chunkIndex);
      formData.append('totalChunks', totalChunks);
      
      console.log("Uploading chunk", chunkIndex, "of", totalChunks, "for fileId:", fileId);
      
      $.ajax({
        url: uploadEndpoint,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          console.log("Chunk", chunkIndex, "uploaded successfully. Server response:", response);
          resolve(response);
        },
        error: function(err) {
          console.error("Error uploading chunk", chunkIndex, "for fileId:", fileId, "Error details:", err);
          reject(err);
        }
      });
    });
  }
  
    
    // Signale au serveur de réassembler les chunks pour un fichier donné
    function finalizeUpload(fileId, fileName) {
      return new Promise(function(resolve, reject) {
        $.ajax({
          url: uploadEndpoint,
          type: 'POST',
          data: { fileId: fileId, finalize: true, fileName: fileName },
          success: function(response) {
            resolve(response);
          },
          error: function(err) {
            reject(err);
          }
        });
      });
    }

    function deleteUploadedMedia(fileId) {
        return new Promise(function(resolve, reject) {
          $.ajax({
            url: uploadEndpoint,  // Utilise le même endpoint (ou un endpoint dédié si vous préférez)
            type: 'POST',
            data: { fileId: fileId, delete: true },
            processData: true,
            success: function(response) {
              console.log('Suppression réussie pour le média ' + fileId, response);
              resolve(response);
            },
            error: function(err) {
              console.error('Erreur lors de la suppression du média ' + fileId, err);
              reject(err);
            }
          });
        });
      }
      
    
    // Fonction d'initialisation du plugin MediaUploader
    function initMediaUploader(options = {}) {
      uploadEndpoint = options.uploadEndpoint || uploadEndpoint;
      
      injectHTML();
      
      let selectBtn = document.getElementById('select-media');
      let fileInput = document.getElementById('media-uploader-input');
      if (selectBtn && fileInput) {
        selectBtn.addEventListener('click', function() {
          fileInput.click();
        });
      }
      
      if (fileInput) {
        fileInput.addEventListener('change', function(e) {
          handleFiles(e.target.files);
        });
      }
      
      let uploadBtn = document.getElementById('upload-media');
      if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
          // Pour chaque fichier sélectionné, lancer l'upload
          filesToUpload.forEach(fileObj => {
            // Si ce fichier n'a pas encore été uploadé, lancer uploadFile
            if (fileObj.uploadedChunks === 0) {
              uploadFile(fileObj);
            }
          });
        });
      }
      
      let closeBtn = document.getElementById('close-media-uploader');
      if (closeBtn) {
        closeBtn.addEventListener('click', function() {
          $('#media-uploader-modal').foundation('reveal', 'close');
        });
      }
      
      console.log("MediaUploader initialisé avec l'endpoint :", uploadEndpoint);
    }
    
    // Expose le plugin globalement
    window.MediaUploader = {
      init: initMediaUploader,
      handleFiles: handleFiles
    };
  })();
  