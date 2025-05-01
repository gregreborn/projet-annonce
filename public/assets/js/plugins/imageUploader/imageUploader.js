// File: imageUploader.js
(function () {
  const MAX_FILES = 4;
  const MAX_FILE_SIZE_MB = 20;
  const MAX_WIDTH = 1200;
  const MAX_HEIGHT = 1200;
  let images = []; // store image objects in order (max 4)
  let activeImageIndex = null;
  let cropper = null;

 

  // Process an image file (resize & optionally compress)
  function processImage(file) {
    return new Promise((resolve) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const img = new Image();
        img.onload = () => {
          let width = img.width;
          let height = img.height;
          // Resize if too large
          if (width > MAX_WIDTH || height > MAX_HEIGHT) {
            const ratio = Math.min(MAX_WIDTH / width, MAX_HEIGHT / height);
            width = Math.floor(width * ratio);
            height = Math.floor(height * ratio);
          }
          const canvas = document.createElement("canvas");
          canvas.width = width;
          canvas.height = height;
          const ctx = canvas.getContext("2d");
          ctx.drawImage(img, 0, 0, width, height);
          canvas.toBlob((blob) => {
            if (blob.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
              // Compress if still too big
              canvas.toBlob((compressedBlob) => resolve(compressedBlob), "image/jpeg", 0.7);
            } else {
              resolve(blob);
            }
          }, "image/jpeg", 0.9);
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    });
  }

  // Update the left-side upload tiles (always 4 tiles)
  function updateImageList() {
    const listContainer = document.querySelector("#image-list-container");
    listContainer.innerHTML = ""; // Effacer le contenu précédent
  
    // Par défaut, sur PC, on affiche MAX_FILES tuiles
    let numberOfTiles = MAX_FILES;
    // Sur mobile/tablette (ex. largeur < 1024px), on affiche images.length + 1 (max MAX_FILES)
    if (window.innerWidth < 1024) {
      numberOfTiles = Math.min(images.length + 1, MAX_FILES);
    }
  
    // Création des tuiles
    for (let i = 0; i < numberOfTiles; i++) {
      const tile = document.createElement("div");
      tile.classList.add("upload-tile");
      // Mettre en évidence la tuile active (si applicable)
      tile.style.border = activeImageIndex === i ? "2px solid blue" : "1px dashed #ccc";
  
      if (images[i]) {
        // S'il y a une image à cet emplacement, afficher son aperçu (croppé si disponible)
        const previewURL = images[i].croppedURL ? images[i].croppedURL : images[i].previewURL;
        const imgElem = document.createElement("img");
        imgElem.src = previewURL;
        tile.appendChild(imgElem);
  
        // Bouton de suppression
        const removeBtn = document.createElement("button");
        removeBtn.innerHTML = "&times;";
        removeBtn.classList.add("remove-btn");
        removeBtn.style.position = "absolute";
        removeBtn.style.top = "2px";
        removeBtn.style.right = "2px";
        removeBtn.style.background = "rgba(255,255,255,0.8)";
        removeBtn.style.border = "none";
        removeBtn.style.cursor = "pointer";
        removeBtn.style.fontSize = "16px";
        removeBtn.style.lineHeight = "1";
        removeBtn.style.padding = "0 4px";
        removeBtn.addEventListener("click", function (e) {
          e.stopPropagation();
          removeImage(i);
        });
        tile.appendChild(removeBtn);
  
        // Bouton "Définir comme miniature" (étoile)
        const thumbnailBtn = document.createElement("button");
        thumbnailBtn.innerHTML = "★";
        thumbnailBtn.classList.add("thumbnail-btn");
        thumbnailBtn.style.position = "absolute";
        thumbnailBtn.style.bottom = "2px";
        thumbnailBtn.style.right = "2px";
        thumbnailBtn.style.background = "rgba(255,255,255,0.8)";
        thumbnailBtn.style.border = "none";
        thumbnailBtn.style.cursor = "pointer";
        thumbnailBtn.style.fontSize = "16px";
        thumbnailBtn.style.lineHeight = "1";
        thumbnailBtn.style.padding = "0 4px";
        thumbnailBtn.addEventListener("click", function (e) {
          e.stopPropagation();
          // Marquer cette image comme miniature et réinitialiser les autres
          images.forEach((img, idx) => {
            img.isThumbnail = (idx === i);
          });
          updateImageList();
          setActiveImage(i);
        });
        // Appliquer la couleur or uniquement pour la miniature sélectionnée
        if (images[i].isThumbnail) {
          tile.classList.add("active-thumbnail");
          thumbnailBtn.style.color = "#f39c12";
        } else {
          thumbnailBtn.style.color = "#ccc";
        }
        tile.appendChild(thumbnailBtn);
  
        // Clic sur la tuile : la définir comme image active pour le recadrage
        tile.addEventListener("click", () => {
          setActiveImage(i);
        });
      } else {
        // S'il n'y a pas d'image à cet emplacement, afficher un placeholder
        const placeholder = document.createElement("div");
        placeholder.classList.add("placeholder");
        placeholder.innerHTML = "&#43;<br><small>Add photo</small>";
        tile.appendChild(placeholder);
        // Clic sur le placeholder : déclencher le file input
        tile.addEventListener("click", () => {
          document.getElementById("image-uploader-input").click();
        });
      }
      listContainer.appendChild(tile);
    }
  }
  

  // Remove an image at a given index
  function removeImage(index) {
    images.splice(index, 1);
    if (activeImageIndex === index) {
      if (images.length > 0) {
        activeImageIndex = 0;
        setActiveImage(activeImageIndex);
      } else {
        activeImageIndex = null;
        const cropperImage = document.getElementById("cropper-image");
        const placeholder = document.getElementById("cropper-placeholder");
        cropperImage.style.display = "none";
        placeholder.style.display = "flex";
        if (cropper) {
          cropper.destroy();
          cropper = null;
        }
      }
    } else if (activeImageIndex > index) {
      activeImageIndex--;
    }
    updateImageList();
  }

  // Set the active image for cropping
  function setActiveImage(index) {
    if (!images[index]) return;
    activeImageIndex = index;
    updateImageList();

    const cropperImage = document.getElementById("cropper-image");
    const placeholder = document.getElementById("cropper-placeholder");
    cropperImage.src = images[index].previewURL;
    placeholder.style.display = "none";
    cropperImage.style.display = "block";
    if (cropper) {
      cropper.destroy();
    }
    // Force 1:1 aspect ratio if this image is the thumbnail; otherwise free crop
    const aspectRatio = images[index].isThumbnail ? 1 : NaN;
    cropper = new Cropper(cropperImage, {
      aspectRatio: aspectRatio,
      viewMode: 1,
    });
    // after `cropper = new Cropper(…)` and before you exit setActiveImage:
    const slider = document.getElementById('rotate-slider');
    const display = document.getElementById('rotate-value');

    // reset slider when you load a new image
    slider.value   = 0;
    display.textContent = '0°';

    // listen to slider
    slider.addEventListener('input', () => {
      const angle = Number(slider.value);
      cropper.rotateTo(angle);
      display.textContent = angle + '°';
    });
    
    // Anchor buttons
    document
    .querySelectorAll('#rotate-anchors button')
    .forEach(btn => {
      btn.addEventListener('click', () => {
        const angle = Number(btn.dataset.angle);
        cropper.rotateTo(angle);
        slider.value = angle;
        display.textContent = angle + '°';
      });ki
    });
  }

  // Confirm crop for the active image
  function confirmCrop() {
    if (cropper && activeImageIndex !== null) {
      cropper.getCroppedCanvas().toBlob((blob) => {
        const croppedURL = URL.createObjectURL(blob);
        images[activeImageIndex].croppedBlob = blob;
        images[activeImageIndex].croppedURL = croppedURL;
        updateImageList();
        const cropperImage = document.getElementById("cropper-image");
        cropperImage.src = croppedURL;
        cropper.destroy();
        // Reinitialize cropper with the same settings
        const aspectRatio = images[activeImageIndex].isThumbnail ? 1 : NaN;
        cropper = new Cropper(cropperImage, {
          aspectRatio: aspectRatio,
          viewMode: 1,
        });
      }, "image/jpeg", 0.9);
    }
  }

  // Handle file uploads from input or drop
  function handleFiles(files) {
    const fileArray = Array.from(files).filter((file) => file.type.startsWith("image/"));
    for (let file of fileArray) {
      if (images.length >= MAX_FILES) break;
      processImage(file).then((processedBlob) => {
        const previewURL = URL.createObjectURL(processedBlob);
        const newImage = {
          file: file,
          processedBlob: processedBlob,
          previewURL: previewURL,
          croppedBlob: null,
          croppedURL: null,
          isThumbnail: false
        };
        images.push(newImage);
        // If no image is set as thumbnail, set the first one by default
        if (!images.some(img => img.isThumbnail)) {
          newImage.isThumbnail = true;
        }
        if (activeImageIndex === null) {
          setActiveImage(0);
        }
        updateImageList();
      });
    }
  }

  // Finalize selection: for demonstration, log final data and close modal
  function finalizeSelection() {
    // Create an array to hold upload promises for each image
    const uploadPromises = images.map((img, idx) => {
      // Use croppedBlob if available; otherwise, fallback to processedBlob
      const blob = img.croppedBlob || img.processedBlob;
      // Create a unique filename (adjust as needed)
      const filename = 'image_' + Date.now() + '_' + idx + '.jpg';
      return uploadImage(blob, filename).then(response => {
        // Update the image object with returned info
        img.filePath = response.filePath;
        img.fileUrl = response.fileUrl;
        img.fileType = response.fileType; // e.g. "image/jpeg"
        return img;
      });
    });
  
    // Wait for all uploads to finish
    Promise.all(uploadPromises)
      .then(uploadedImages => {
        // Build the final payload
        const finalData = {
          images: uploadedImages.map(img => ({
            filePath: img.filePath,
            fileUrl: img.fileUrl,
            fileType: img.fileType,
            isThumbnail: img.isThumbnail
          })),
          thumbnailIndex: uploadedImages.findIndex(img => img.isThumbnail)
        };
        
        // Set the hidden field value (JSON string) for form submission
        document.getElementById('uploadedImages').value = JSON.stringify(finalData.images);
        
        console.log("Final images data:", finalData);
        // Close the modal
        $('#image-uploader-modal').foundation('reveal', 'close');
      })
      .catch(err => {
        console.error("Error uploading images", err);
        // You could display an error message here
      });
  }
  

   // Initialiser le plugin et attacher les écouteurs
  function initImageUploader(options = {}) {
     // Override the default uploadEndpoint if provided
     uploadEndpoint = options.uploadEndpoint || uploadEndpoint;

    // Injection dynamique du HTML si nécessaire
    if (!document.getElementById("image-uploader-modal")) {
      const modalHTML = `
      <div id="image-uploader-modal" class="reveal-modal" data-reveal aria-hidden="true" role="dialog">
        <div class="modal">
          <a class="close-reveal-modal" aria-label="Close">&#215;</a>
          <h3>Télécharger et recadrer des images</h3>
          <input type="file" id="image-uploader-input" multiple accept="image/*" style="display:none;">
          <div id="upload-container">
            <div id="image-list-container">
              <!-- Les tuiles seront insérées ici -->
            </div>
            <div id="cropper-section">
              <div id="cropper-placeholder">Sélectionnez une image à recadrer</div>
              <img id="cropper-image" src="" alt="Cropper Image" style="display:none;">
              <div style="margin-top:8px;">
                Rotate: 
                <input 
                  type="range" 
                  id="rotate-slider" 
                  min="-180" 
                  max="180" 
                  value="0" 
                  style="width:200px;" 
                />
                <span id="rotate-value">0°</span>
              </div>
              <div id="rotate-anchors" style="margin-top: 4px;">
                <button type="button" data-angle="0">0°</button>
                <button type="button" data-angle="45">45°</button>
                <button type="button" data-angle="90">90°</button>
                <button type="button" data-angle="180">180°</button>
                <button type="button" data-angle="-45">-45°</button>
                <button type="button" data-angle="-90">-90°</button>
              </div>
            </div>
          </div>
          <div id="button-group" style="text-align: right; margin-top: 10px;">
            <button id="confirm-crop" class="button success">Confirmer le recadrage</button>
            <button id="save-changes" class="button success">Enregistrer les modifications</button>
            <button id="close-uploader" class="button alert">Fermer</button>
          </div>
        </div>
      </div>
      <div class="reveal-modal-bg"></div>
      `;
      const container = document.createElement("div");
      container.innerHTML = modalHTML;
      document.body.appendChild(container);
      $(document).foundation();
    }
  
    const modalEl = document.getElementById("image-uploader-modal");
    const fileInput = document.getElementById("image-uploader-input");
    const listContainer = document.getElementById("image-list-container");
    const saveChangesBtn = document.getElementById("save-changes");
    if (saveChangesBtn) {
      saveChangesBtn.addEventListener("click", finalizeSelection);
    }
    if (!modalEl || !fileInput || !listContainer) {
      console.error("ImageUploader: Missing required elements.");
      return;
    }
    fileInput.addEventListener("change", function (e) {
      handleFiles(e.target.files);
      fileInput.value = "";
    });
    listContainer.addEventListener("dragover", function (e) {
      e.preventDefault();
      listContainer.classList.add("dragover");
    });
    listContainer.addEventListener("dragleave", function (e) {
      e.preventDefault();
      listContainer.classList.remove("dragover");
    });
    listContainer.addEventListener("drop", function (e) {
      e.preventDefault();
      listContainer.classList.remove("dragover");
      if (e.dataTransfer && e.dataTransfer.files) {
        handleFiles(e.dataTransfer.files);
      }
    });
    const confirmBtn = document.querySelector("#confirm-crop");
    if (confirmBtn) {
      confirmBtn.addEventListener("click", confirmCrop);
    }
    updateImageList();
    window.addEventListener("resize", updateImageList);
  }

  // Updated uploadImage: uses the configurable uploadEndpoint
  function uploadImage(blob, filename) {
    return new Promise(function(resolve, reject) {
      const formData = new FormData();
      formData.append("file", blob, filename);
  
      $.ajax({
        url: uploadEndpoint,  // now using the configurable endpoint
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          // Expect response to be JSON with properties: filePath, fileUrl, fileType, etc.
          resolve(response);
        },
        error: function(err) {
          reject(err);
        }
      });
    });
  }
  

  // Expose the uploader globally
  window.ImageUploader = {
    init: initImageUploader,
  };
})();
