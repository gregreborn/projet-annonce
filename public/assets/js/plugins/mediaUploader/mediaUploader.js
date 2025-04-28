(function() {
    /******************************************
     * SECTION 1 : CONSTANTES ET VARIABLES
     ******************************************/
    const MAX_FILE_SIZE = 15 * 1024 * 1024; // 15 MB
    const CHUNK_SIZE = 15 * 1024 * 1024;    // Taille d'un chunk (15 MB)
    let filesToUpload = [];                  // Tableau pour stocker les fichiers à uploader et leur état
    let uploadEndpoint = 'assets/js/plugins/mediaUploader/media_endpoint.php'; // Endpoint par défaut
    
    // Table de correspondance entre les types MIME et les extensions
    const MIME_EXTENSION_MAP = {
      'video/mp4': '.mp4',
      'video/webm': '.webm',
      'video/ogg': '.ogg',
      'application/pdf': '.pdf',
      'application/msword': '.doc',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document': '.docx'
    };

    /******************************************
     * SECTION 2 : FONCTIONS UTILITAIRES
     ******************************************/
    /**
     * Retourne un nom de fichier incluant une extension.
     * @param {File} file - L’objet File pour lequel on veut un nom valide.
     * @returns {string} - Le nom de fichier avec extension.
     */
    function getFinalFileName(file) {
      const { name, type } = file;
      if (name.includes('.')) {
        return name;
      }
      const extension = MIME_EXTENSION_MAP[type] || '.mp4';
      return name + extension;
    }
    
    // Affiche un message d'erreur dans le DOM
    function showError(message) {
      const err = document.getElementById('media-error-container');
      if (!err) return;
      err.textContent = message;
      err.style.display = 'block';
    }
    
    // Masque le message d'erreur
    function hideError() {
      const err = document.getElementById('media-error-container');
      if (!err) return;
      err.textContent = '';
      err.style.display = 'none';
    }
    
    /******************************************
     * SECTION 3 : INJECTION DU CODE HTML
     ******************************************/
    function injectHTML() {
      if (!document.getElementById("media-uploader-modal")) {
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    
        const dropzoneHTML = isTouchDevice ? '' : `
          <div id="media-dropzone" class="media-dropzone">
            <i class="material-icons">cloud_upload</i>
            <p>Glissez-déposez vos fichiers ici</p>
          </div>
        `;
    
        const modalHTML = `
          <div id="media-uploader-modal" class="reveal-modal" data-reveal aria-hidden="true" role="dialog">
            <div class="modal" style="position:relative; padding:20px;">
              <a class="close-reveal-modal" aria-label="Close">&#215;</a>
              <h3>Télécharger des médias (vidéo/doc/pdf)</h3>
    
              <div id="media-error-container" 
                   class="alert-box alert" 
                   style="display:none; margin-bottom:10px; padding:8px; border-radius:4px;">
              </div>
    
              ${dropzoneHTML} <!-- Conditional Dropzone Here -->
    
              <button id="select-media" class="button">Sélectionner un fichier</button>
              <input type="file" id="media-uploader-input" multiple 
                     accept="video/mp4,video/webm,video/ogg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
                     style="display:none;">
    
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
    
        // Ajout des événements pour la zone de dépôt si l'appareil n'est pas tactile
        if (!isTouchDevice) {
          const dropzone = document.getElementById('media-dropzone');
    
          dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
          });
    
          dropzone.addEventListener('dragleave', function () {
            dropzone.classList.remove('dragover');
          });
    
          dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const files = Array.from(e.dataTransfer.files);
            if (files.length > 0) {
              handleFiles(files);
            }
          });
        }
    
        const modal = document.getElementById("media-uploader-modal");
        $(modal).foundation(); // Activation de Foundation Reveal
      }
    }
    
    /******************************************
     * SECTION 4 : GESTION DES ÉVÉNEMENTS GLOBAUX
     ******************************************/
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('toggle-section')) {
        const next = e.target.nextElementSibling;
        if (next && next.classList.contains('media-type-group')) {
          next.style.display = next.style.display === 'none' ? 'grid' : 'none';
        }
      }
    });
    
    /******************************************
     * SECTION 5 : GESTION DES FICHIERS ET DE LEURS TUILES
     ******************************************/
    // Génère un identifiant unique pour un fichier
    function generateFileId() {
      return 'file_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
    }
    
    // Crée la tuile visuelle pour un fichier
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
          iconHTML = '<i class="material-icons" style="font-size:48px;">description</i>';
      }
        
      // Création de la tuile
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
        
      // Événement de suppression de la tuile
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
      
    // Supprime la tuile et le fichier correspondant
    function removeFileTile(fileId) {
      filesToUpload = filesToUpload.filter(f => f.fileId !== fileId);
      let tile = document.querySelector(`.media-tile[data-fileid="${fileId}"]`);
      if (tile && tile.parentNode) {
        tile.parentNode.removeChild(tile);
      }
      resetFileInput();
      updateUploadButtonState();
    }
    
    // Met à jour les compteurs de tuiles affichés
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
    
    /******************************************
     * SECTION 6 : GESTION DE LA SÉLECTION ET DU TRAITEMENT DES FICHIERS
     ******************************************/
    function resetFileInput() {
      const old = document.getElementById('media-uploader-input');
      const parent = old.parentNode;
      const clone = old.cloneNode();
      clone.id = old.id;
      clone.multiple = old.multiple;
      clone.accept = old.accept;
      clone.style.display = 'none';
      clone.addEventListener('change', onFileInputChange);
      parent.replaceChild(clone, old);
    }
    
    function onFileInputChange(e) {
      handleFiles(e.target.files);
      e.target.value = '';

    }
    
    // Traite les fichiers sélectionnés
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
    
    /******************************************
     * SECTION 7 : UPLOAD DES FICHIERS
     ******************************************/
    function uploadFile(fileObj) {
      console.log("Uploading file", fileObj.file.name, "Size:", fileObj.file.size, "Total chunks:", fileObj.totalChunks);
      if (fileObj.file.size <= CHUNK_SIZE) {
        let formData = new FormData();
        formData.append('file', fileObj.file);
        formData.append('fileId', fileObj.fileId);
        formData.append('chunkIndex', 0);
        formData.append('totalChunks', 1);
        formData.append('fileName', fileObj.file.name);
    
        $.ajax({
          url: uploadEndpoint,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success(response) {
            console.log('Upload direct réussi :', response);
            updateTileProgress(fileObj.fileId, 100);
            fileObj.status = 'uploaded';
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
            return finalizeUpload(fileObj.fileId, getFinalFileName(fileObj.file));
          })
          .then(finalResponse => {
            console.log('Fichier final réassemblé:', finalResponse);
            updateTileProgress(fileObj.fileId, 100);
          })
          .catch(err => {
            console.error('Erreur lors de l’upload d’un chunk ou de la finalisation:', err);
          })
          .finally(() => {
            fileObj.status = 'uploaded';
          });
      }
    }
    
    /******************************************
     * SECTION 8 : UPLOAD DES CHUNKS 
     ******************************************/
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
    
    /******************************************
     * SECTION 9 : FINALISATION DE L'UPLOAD
     ******************************************/
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
    
    function finalizeAllUploads() {
      filesToUpload.forEach(fileObj => {
        if (fileObj.uploadedChunks === fileObj.totalChunks) {
          finalizeUpload(fileObj.fileId)
            .then(finalResponse => {
              console.log('Fichier final réassemblé:', finalResponse);
              updateTileProgress(fileObj.fileId, 100);
            })
            .catch(err => console.error('Erreur lors de la finalisation:', err));
        }
      });
    }
    
    /******************************************
     * SECTION 10 : SUPPRESSION DES MÉDIAS UPLOADÉS
     ******************************************/
    function deleteUploadedMedia(fileId) {
      return new Promise(function(resolve, reject) {
        $.ajax({
          url: uploadEndpoint,
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
      
    /******************************************
     * SECTION 11 : INITIALISATION DU PLUGIN MEDIA UPLOADER
     ******************************************/
    function initMediaUploader(options = {}) {
      uploadEndpoint = options.uploadEndpoint || uploadEndpoint;
      injectHTML();
    
      const selectBtn  = document.getElementById('select-media');
      let fileInput  = document.getElementById('media-uploader-input');
      const uploadBtn  = document.getElementById('upload-media');
      const closeBtn   = document.getElementById('close-media-uploader');
    
      // Gestionnaire pour l'input de fichier
      function onFileInputChange(e) {
        hideError();
        handleFiles(e.target.files);
      
        e.target.value = '';
        resetFileInput();
      }
      
      function resetFileInput() {
        const newInput = fileInput.cloneNode(false);
        newInput.id       = fileInput.id;
        newInput.multiple = fileInput.multiple;
        newInput.accept   = fileInput.accept;
        newInput.style.display = 'none';
        newInput.addEventListener('change', onFileInputChange);
        fileInput.parentNode.replaceChild(newInput, fileInput);
        fileInput = newInput;
      }
    
      // 1) Brancher le listener sur l'input
      fileInput.addEventListener('change', onFileInputChange);
    
      // 2) Sélection du fichier
      selectBtn.addEventListener('click', () => {
        hideError();
        fileInput.click();
      });
    
      // 3) Upload des fichiers
      uploadBtn.addEventListener('click', () => {
        filesToUpload.forEach(f => {
          if (!f.status || f.status !== 'uploaded') uploadFile(f);
        });        
      });
    
      // 4) Fermeture du modal
      closeBtn.addEventListener('click', () => {
        $('#media-uploader-modal').foundation('reveal', 'close');
        hideError();
      });
    
      console.log("MediaUploader initialisé avec l'endpoint :", uploadEndpoint);
    }
    
    /******************************************
     * SECTION 12 : EXPOSITION DU PLUGIN
     ******************************************/
    window.MediaUploader = {
      init: initMediaUploader,
      handleFiles: handleFiles
    };
  
})();
