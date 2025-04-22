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
    function showError(message) {
      const err = document.getElementById('media-error-container');
      if (!err) return;
      err.textContent = message;
      err.style.display = 'block';
    }
    
    function hideError() {
      const err = document.getElementById('media-error-container');
      if (!err) return;
      err.textContent = '';
      err.style.display = 'none';
    }
    
    // Injecte la modale dans le DOM si elle n'existe pas déjà
    function injectHTML() {
      if (!document.getElementById("media-uploader-modal")) {
        const modalHTML = `
        <div id="media-uploader-modal" class="reveal-modal" data-reveal aria-hidden="true" role="dialog">
          <div class="modal" style="position:relative; padding:20px;">
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
            <h3>Télécharger des médias (vidéo/doc/pdf)</h3>
            
            <!-- Zone d'erreur -->
            <div id="media-error-container" 
                class="alert-box alert" 
                style="display:none; margin-bottom:10px; padding:8px; border-radius:4px;">
            </div>

            <!-- Bouton visible pour sélectionner un fichier -->
            <button id="select-media" class="button">Sélectionner un fichier</button>
            <!-- Input de type file masqué -->
            <input type="file" id="media-uploader-input" multiple accept="video/mp4,video/webm,video/ogg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" style="display:none;">
            <!-- Zone d'affichage des tuiles pour chaque fichier sélectionné -->
            <div id="media-tile-container">
              <h4 id="video-count" class="toggle-section" style="cursor:pointer;">📹 Vidéos</h4>
              <div id="media-videos" class="media-type-group"></div>
              
              <h4 id="doc-count" class="toggle-section" style="cursor:pointer;">📄 Documents</h4>
              <div id="media-documents" class="media-type-group"></div>
            </div>
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

        const modal = document.getElementById("media-uploader-modal");
        $(modal).foundation(); // ⬅ THIS is what makes Reveal work
      }
    }
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('toggle-section')) {
        const next = e.target.nextElementSibling;
        if (next && next.classList.contains('media-type-group')) {
          next.style.display = next.style.display === 'none' ? 'grid' : 'none';
        }
      }
    });
    
    // Génère un identifiant unique pour un fichier
    function generateFileId() {
      return 'file_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
    }
    
    function createFileTile(fileObj) {
      let tileContainer;
      if (fileObj.file.type.startsWith("video/")) {
        tileContainer = document.getElementById("media-videos");
      } else {
        tileContainer = document.getElementById("media-documents");
      }
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
        updateSectionCounts();
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
    
      // Réinitialiser l'input fichier
      resetFileInput();
      updateUploadButtonState();
    }

    function updateSectionCounts() {
      const videoCount = document.querySelectorAll("#media-videos .media-tile").length;
      const docCount = document.querySelectorAll("#media-documents .media-tile").length;
    
      const videoCountEl = document.getElementById("video-count-number");
      const docCountEl = document.getElementById("doc-count-number");
    
      if (videoCountEl) videoCountEl.textContent = videoCount;
      if (docCountEl) docCountEl.textContent = docCount;
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
    function resetFileInput() {
      const old = document.getElementById('media-uploader-input');
      const parent = old.parentNode;
      const clone = old.cloneNode();
      // 👉 on remet exactement les mêmes props / listeners
      clone.id = old.id;
      clone.multiple = old.multiple;
      clone.accept = old.accept;
      clone.style.display = 'none';
      // on ré-attache le listener de change
      clone.addEventListener('change', onFileInputChange);
      parent.replaceChild(clone, old);
    }
    function onFileInputChange(e) {
      handleFiles(e.target.files);
      // inutile de vider la valeur ici
    }
    
    // Gère la sélection de fichiers depuis l'input masqué
    function handleFiles(files) {
      hideError();
    
      const currentCount = filesToUpload.length;
      const newFiles = Array.from(files);
    
      if (currentCount + newFiles.length > 10) {
        showError(`Vous pouvez télécharger un maximum de 10 médias. Actuellement sélectionnés: ${currentCount}.`);
        return;
      }
    
      newFiles.forEach(file => {
        const allowedTypes = [
          'video/mp4', 'video/webm', 'video/ogg',
          'application/pdf',
          'application/msword',
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
    
        if (!allowedTypes.includes(file.type)) {
          showError(`Format non pris en charge : "${file.name}".`);
          return;
        }
    
        const fileObj = {
          file,
          fileId: generateFileId(),
          totalChunks: Math.ceil(file.size / CHUNK_SIZE),
          uploadedChunks: 0,
          progress: 0
        };
    
        filesToUpload.push(fileObj);
        createFileTile(fileObj);
        updateUploadButtonState();
      });
    }
    function updateUploadButtonState() {
      const uploadBtn = document.getElementById('upload-media');
      if (!uploadBtn) return;
    
      const count = filesToUpload.length;
      uploadBtn.disabled = count === 0 || count > 10;
    }
    
    
    
    
    // Upload d'un fichier, en découpant en chunks si nécessaire
    function uploadFile(fileObj) {
      console.log("Uploading file", fileObj.file.name, "Size:", fileObj.file.size, "Total chunks:", fileObj.totalChunks);
      if (fileObj.file.size <= CHUNK_SIZE) {
        // construisons nous‑mêmes la FormData pour un seul chunk
        let formData = new FormData();
        formData.append('file', fileObj.file);
        formData.append('fileId', fileObj.fileId);
        formData.append('chunkIndex', 0);
        formData.append('totalChunks', 1);
        formData.append('fileName', fileObj.file.name);  // ! indispensable
    
        $.ajax({
          url: uploadEndpoint,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success(response) {
            console.log('Upload direct réussi :', response);
            updateTileProgress(fileObj.fileId, 100);
          },
          error(err) {
            console.error('Erreur upload direct :', err);
          }
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
    
      // Récupère nos éléments
      const selectBtn  = document.getElementById('select-media');
      let   fileInput  = document.getElementById('media-uploader-input');
      const uploadBtn  = document.getElementById('upload-media');
      const closeBtn   = document.getElementById('close-media-uploader');
    
      // Handlers d’erreur
      function onFileInputChange(e) {
        // on cache toute erreur précédente
        hideError();
        // on traite les fichiers
        handleFiles(e.target.files);
        // on ne recrée l’input *qu’après* avoir affiché une éventuelle erreur,
        // afin que showError ait le temps d’agir sur l’élément actuel
        resetFileInput();
      }
      function resetFileInput() {
        const newInput = fileInput.cloneNode(false);
        // recopier les attributs
        newInput.id       = fileInput.id;
        newInput.multiple = fileInput.multiple;
        newInput.accept   = fileInput.accept;
        newInput.style.display = 'none';
        newInput.addEventListener('change', onFileInputChange);
        fileInput.parentNode.replaceChild(newInput, fileInput);
        fileInput = newInput;
      }
    
      // 1) on branche le listener de changement
      fileInput.addEventListener('change', onFileInputChange);
    
      // 2) Sélecteur de fichier
      selectBtn.addEventListener('click', () => {
        hideError();      // clear any old error before opening
        fileInput.click();
      });
    
      // 3) Upload
      uploadBtn.addEventListener('click', () => {
        filesToUpload.forEach(f => {
          if (f.uploadedChunks === 0) uploadFile(f);
        });
      });
    
      // 4) Fermeture
      closeBtn.addEventListener('click', () => {
        $('#media-uploader-modal').foundation('reveal', 'close');
        hideError(); // on nettoie l’erreur pour la prochaine ouverture
      });
    
      console.log("MediaUploader initialisé avec l'endpoint :", uploadEndpoint);
    }
    
    
    
    // Expose le plugin globalement
    window.MediaUploader = {
      init: initMediaUploader,
      handleFiles: handleFiles
    };
  })();
  