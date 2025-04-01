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
    listContainer.innerHTML = ""; // clear previous content

    // Create 4 tiles (indices 0 ... MAX_FILES-1)
    for (let i = 0; i < MAX_FILES; i++) {
      const tile = document.createElement("div");
      tile.classList.add("upload-tile");
      // Highlight active tile if applicable
      tile.style.border = activeImageIndex === i ? "2px solid blue" : "1px dashed #ccc";

      if (images[i]) {
        // If an image exists at this slot, show its preview
        const previewURL = images[i].croppedURL ? images[i].croppedURL : images[i].previewURL;
        const imgElem = document.createElement("img");
        imgElem.src = previewURL;
        tile.appendChild(imgElem);

        // Add remove button
        const removeBtn = document.createElement("button");
        removeBtn.innerHTML = "&times;"; // X character
        removeBtn.classList.add("remove-btn");
        // Style inline or via CSS class (positioned at top-right)
        removeBtn.style.position = "absolute";
        removeBtn.style.top = "2px";
        removeBtn.style.right = "2px";
        removeBtn.style.background = "rgba(255,255,255,0.8)";
        removeBtn.style.border = "none";
        removeBtn.style.cursor = "pointer";
        removeBtn.style.fontSize = "16px";
        removeBtn.style.lineHeight = "1";
        removeBtn.style.padding = "0 4px";
        
        // Prevent tile click when remove is clicked
        removeBtn.addEventListener("click", function (e) {
          e.stopPropagation();
          removeImage(i);
        });
        tile.appendChild(removeBtn);

        // Clicking an image tile sets it as active for cropping
        tile.addEventListener("click", () => {
          setActiveImage(i);
        });
      } else {
        // Placeholder tile: show a plus icon and label
        const placeholder = document.createElement("div");
        placeholder.classList.add("placeholder");
        placeholder.innerHTML = "&#43;<br><small>Add photo</small>";
        tile.appendChild(placeholder);
        // Clicking an empty tile triggers the file input
        tile.addEventListener("click", () => {
          document.getElementById("image-uploader-input").click();
        });
      }
      listContainer.appendChild(tile);
    }
  }

  // New function to remove an image at a given index
  function removeImage(index) {
    // Remove the image from the array
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
        placeholder.style.display = "flex"; // Show the placeholder
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
    if (!images[index]) return; // no image there
    activeImageIndex = index;
    updateImageList(); // update highlight
  
    const cropperImage = document.getElementById("cropper-image");
    const placeholder = document.getElementById("cropper-placeholder");
  
    // Always use the original processed image for cropping
    cropperImage.src = images[index].previewURL;
    
    // Hide the placeholder and show the cropper image
    placeholder.style.display = "none";
    cropperImage.style.display = "block";
    
    // Destroy previous cropper instance if it exists
    if (cropper) {
      cropper.destroy();
    }
    cropper = new Cropper(cropperImage, {
      aspectRatio: 1, // square crop; adjust as needed
      viewMode: 1,
    });
  }

  // Confirm the crop for the active image
  function confirmCrop() {
    if (cropper && activeImageIndex !== null) {
      cropper.getCroppedCanvas().toBlob((blob) => {
        const croppedURL = URL.createObjectURL(blob);
        images[activeImageIndex].croppedBlob = blob;
        images[activeImageIndex].croppedURL = croppedURL;
        updateImageList();
        // Optionally update the cropper with the cropped version
        const cropperImage = document.querySelector("#cropper-image");
        cropperImage.src = croppedURL;
        cropper.destroy();
        cropper = new Cropper(cropperImage, {
          aspectRatio: 1,
          viewMode: 1,
        });
      }, "image/jpeg", 0.9);
    }
  }

  // Handle file uploads (from file input or drop)
  function handleFiles(files) {
    const fileArray = Array.from(files).filter((file) => file.type.startsWith("image/"));
    // Only add files if there is room (max MAX_FILES)
    for (let file of fileArray) {
      if (images.length >= MAX_FILES) break;
      processImage(file).then((processedBlob) => {
        const previewURL = URL.createObjectURL(processedBlob);
        images.push({
          file: file,
          processedBlob: processedBlob,
          previewURL: previewURL,
          croppedBlob: null,
          croppedURL: null,
        });
        // If no active image yet, set the first one active
        if (activeImageIndex === null) {
          setActiveImage(0);
        }
        updateImageList();
      });
    }
  }

  // Initialize the uploader: attach event listeners, etc.
  function initImageUploader(options = {}) {
    const modalSelector = options.modalSelector || "#image-uploader-modal";
    const fileInputSelector = options.fileInputSelector || "#image-uploader-input";
    const modalEl = document.querySelector(modalSelector);
    const fileInput = document.querySelector(fileInputSelector);
    const listContainer = document.querySelector("#image-list-container");
    const saveChangesBtn = document.getElementById("save-changes");
    if (saveChangesBtn) {
      saveChangesBtn.addEventListener("click", finalizeSelection);
    }

    if (!modalEl || !fileInput || !listContainer) {
      console.error("ImageUploader: Missing required elements.");
      return;
    }
    // File input change
    fileInput.addEventListener("change", function (e) {
      handleFiles(e.target.files);
      fileInput.value = ""; // reset for subsequent uploads
    });
    // Enable drag & drop on the left container (tiles area)
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
    // Bind the confirm crop button
    const confirmBtn = document.querySelector("#confirm-crop");
    if (confirmBtn) {
      confirmBtn.addEventListener("click", confirmCrop);
    }
    updateImageList();
  }
  function finalizeSelection() {
    const finalData = {
      images: images.map(img => ({
        previewURL: img.previewURL,
        croppedURL: img.croppedURL
      })),
      thumbnailIndex: activeImageIndex
    };
  
    // For demonstration
    console.log("Final images data:", finalData);
  
    // Example of storing in hidden form input (optional)
    /*
    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "uploadedImages";
    hiddenInput.value = JSON.stringify(finalData);
    document.querySelector("#my-form").appendChild(hiddenInput);
    */
  
    // Close modal (if using Foundation Reveal)
    $('#image-uploader-modal').foundation('reveal', 'close');
  }
  
  

  // Expose our uploader globally
  window.ImageUploader = {
    init: initImageUploader,
  };
})();
