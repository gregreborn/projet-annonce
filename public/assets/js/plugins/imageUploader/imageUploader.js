// File: imageUploader.js

(function () {
    const MAX_FILE_SIZE_MB = 20;
    const MAX_WIDTH = 1200;
    const MAX_HEIGHT = 1200;
  
    const initImageUploader = (inputSelector = "#image-uploader", previewContainer = "#image-preview-container") => {
      const input = document.querySelector(inputSelector);
      const container = document.querySelector(previewContainer);
  
      if (!input || !container) return;
  
      input.addEventListener("change", async (e) => {
        container.innerHTML = ""; // Clear previews
      
        const files = Array.from(e.target.files).slice(0, 4); // Max 4 files
        const dt = new DataTransfer(); // ✅ Only create once outside the loop
      
        for (let file of files) {
          if (!file.type.startsWith("image/")) continue;
        
          const imgBlob = await processImage(file);
          const previewURL = URL.createObjectURL(imgBlob);
        
          const wrapper = document.createElement("div");
          wrapper.style.position = "relative";
          wrapper.style.margin = "5px";
        
          const img = document.createElement("img");
          img.src = previewURL;
          img.style.maxWidth = "100px";
          img.style.borderRadius = "6px";
          img.style.display = "block";
        
          const btn = document.createElement("button");
          btn.textContent = "📸 Miniature";
          btn.classList.add("thumbnail-picker", "button", "tiny", "secondary");
          btn.type = "button"; // 👈 Prevent form submission!
          btn.style.marginTop = "5px";

        
          // Add image then button to the wrapper
          wrapper.appendChild(img);
          wrapper.appendChild(btn);
        
          container.appendChild(wrapper);
          dt.items.add(new File([imgBlob], file.name, { type: "image/jpeg" }));
        }
        
      
        if (thumbnailBlob) {
          dt.items.add(new File([thumbnailBlob], thumbnailName, { type: "image/jpeg" }));
          console.log("✅ Thumbnail added:", thumbnailBlob);
        }

        input.files = dt.files; // ✅ Set once after loop
      });
      
    };
  
    async function processImage(file) {
      return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const img = new Image();
          img.onload = () => {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");
  
            // Resize logic
            let width = img.width;
            let height = img.height;
            if (width > MAX_WIDTH || height > MAX_HEIGHT) {
              const ratio = Math.min(MAX_WIDTH / width, MAX_HEIGHT / height);
              width *= ratio;
              height *= ratio;
            }
  
            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(img, 0, 0, width, height);
  
            canvas.toBlob((blob) => {
              if (blob.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                // Compress by reducing quality if still too big
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
  
    let cropper;
let thumbnailBlob = null;
let thumbnailName = "";

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("thumbnail-picker")) {
    console.log("🖼️ Thumbnail button clicked!");

    const img = e.target.previousElementSibling;
    const modal = document.getElementById("thumbnail-crop-modal");
    const cropImg = document.getElementById("cropper-image");
    cropImg.src = img.src;

    const modalJQ = $('#thumbnail-crop-modal');
    console.log("📦 Modal found:", modalJQ);
    console.log("🧪 Reveal data:", modalJQ.data('reveal'));

   

    modalJQ.foundation('open');
    setTimeout(() => {
      cropper = new Cropper(cropImg, {
        aspectRatio: 1,
        viewMode: 1
      });
    }, 200);
  }
});


document.getElementById("confirm-thumbnail").addEventListener("click", () => {
  if (!cropper) return;

  cropper.getCroppedCanvas().toBlob((blob) => {
    thumbnailBlob = blob;
    thumbnailName = "thumbnail.jpg";
  
    // ✅ Optional visual feedback
    const previews = document.querySelectorAll("#image-preview-container img");
    previews.forEach(img => img.classList.remove("selected-thumbnail"));
    const selected = Array.from(previews).find(img => img.src === document.getElementById("cropper-image").src);
    if (selected) selected.classList.add("selected-thumbnail");
  
    cropper.destroy();
    $('#thumbnail-crop-modal').foundation('close');
    $('.reveal-modal-bg').hide();
  }, "image/jpeg", 0.9);
  
});


    // Auto init (optional)
    document.addEventListener("DOMContentLoaded", () => initImageUploader());
  })();
  