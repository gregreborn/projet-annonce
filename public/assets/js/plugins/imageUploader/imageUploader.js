// File: imageUploader.js
(function () {
  const MAX_FILES = 4;
  const MAX_FILE_SIZE_MB = 20;
  const MAX_WIDTH = 1200;
  const MAX_HEIGHT = 1200;
  let images = []; // array to store image objects
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

  // Update the left-side image list
  function updateImageList() {
    const listContainer = document.querySelector("#image-list-container");
    listContainer.innerHTML = ""; // Clear previous content
    images.forEach((imgObj, index) => {
      const card = document.createElement("div");
      card.classList.add("image-card");
      card.style.border = activeImageIndex === index ? "2px solid blue" : "1px solid #ccc";
      card.style.borderRadius = "8px";
      card.style.padding = "5px";
      card.style.margin = "5px";
      card.style.cursor = "pointer";

      // Use the cropped preview if available; otherwise, show the original processed preview
      const previewURL = imgObj.croppedURL ? imgObj.croppedURL : imgObj.previewURL;
      const imageElem = document.createElement("img");
      imageElem.src = previewURL;
      imageElem.style.width = "100%";
      imageElem.style.display = "block";
      card.appendChild(imageElem);

      // When clicked, make this image active for cropping
      card.addEventListener("click", () => {
        setActiveImage(index);
      });
      listContainer.appendChild(card);
    });
  }

  // Set the active image to be cropped
  function setActiveImage(index) {
    activeImageIndex = index;
    updateImageList(); // Update selection highlight
    const cropperImage = document.querySelector("#cropper-image");
    // Always show the original processed image for cropping
    cropperImage.src = images[index].previewURL;
    // Destroy previous cropper instance if it exists
    if (cropper) {
      cropper.destroy();
    }
    // Initialize CropperJS on the image
    cropper = new Cropper(cropperImage, {
      aspectRatio: 1, // For a square crop; adjust as needed
      viewMode: 1,
    });
  }

  // Confirm the crop for the active image
  function confirmCrop() {
    if (cropper && activeImageIndex !== null) {
      cropper.getCroppedCanvas().toBlob((blob) => {
        // Create a URL for the cropped image
        const croppedURL = URL.createObjectURL(blob);
        // Store the cropped version in the images array
        images[activeImageIndex].croppedBlob = blob;
        images[activeImageIndex].croppedURL = croppedURL;
        // Update the image list with the new cropped preview
        updateImageList();
        // Optionally, update the cropping area to show the cropped result
        const cropperImage = document.querySelector("#cropper-image");
        cropperImage.src = croppedURL;
        // Reinitialize CropperJS if the user wants to adjust further
        cropper.destroy();
        cropper = new Cropper(cropperImage, {
          aspectRatio: 1,
          viewMode: 1,
        });
      }, "image/jpeg", 0.9);
    }
  }

  // Handle file uploads (from input or drag & drop)
  function handleFiles(files) {
    const fileArray = Array.from(files)
      .filter((file) => file.type.startsWith("image/"))
      .slice(0, MAX_FILES);
    fileArray.forEach(async (file) => {
      const processedBlob = await processImage(file);
      const previewURL = URL.createObjectURL(processedBlob);
      // Add image object to our array
      images.push({
        file: file,
        processedBlob: processedBlob,
        previewURL: previewURL,
        croppedBlob: null,
        croppedURL: null,
      });
      // If this is the first image, set it as active
      if (activeImageIndex === null) {
        setActiveImage(0);
      }
      updateImageList();
    });
  }

  // Initialize the uploader: attach event listeners, etc.
  function initImageUploader(options = {}) {
    const modalSelector = options.modalSelector || "#image-uploader-modal";
    const dropZoneSelector = options.dropZoneSelector || "#image-drop-zone";
    const fileInputSelector = options.fileInputSelector || "#image-uploader-input";
    const modalEl = document.querySelector(modalSelector);
    const dropZone = document.querySelector(dropZoneSelector);
    const fileInput = document.querySelector(fileInputSelector);
    if (!modalEl || !dropZone || !fileInput) {
      console.error("ImageUploader: Missing required elements.");
      return;
    }
    fileInput.addEventListener("change", function (e) {
      handleFiles(e.target.files);
      fileInput.value = ""; // Reset for subsequent uploads
    });
    dropZone.addEventListener("dragover", function (e) {
      e.preventDefault();
      dropZone.classList.add("dragover");
    });
    dropZone.addEventListener("dragleave", function (e) {
      e.preventDefault();
      dropZone.classList.remove("dragover");
    });
    dropZone.addEventListener("drop", function (e) {
      e.preventDefault();
      dropZone.classList.remove("dragover");
      if (e.dataTransfer && e.dataTransfer.files) {
        handleFiles(e.dataTransfer.files);
      }
    });
    // Bind the confirm crop button
    const confirmBtn = document.querySelector("#confirm-crop");
    if (confirmBtn) {
      confirmBtn.addEventListener("click", confirmCrop);
    }
  }

  // Expose the uploader
  window.ImageUploader = {
    init: initImageUploader,
  };
})();
