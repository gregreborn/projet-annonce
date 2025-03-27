console.log("✅ base.js loaded!");

document.addEventListener("DOMContentLoaded", function () {
  console.log("🔍 PristineJS chargé ?", typeof Pristine !== "undefined");

  const forms = document.querySelectorAll("form");

  forms.forEach(form => {
    console.log("📌 Formulaire détecté :", form);

    if (typeof Pristine === "undefined") {
      console.error("❌ PristineJS n'est pas chargé !");
      return;
    }

    // 🛠️ Initialize PristineJS for each form
    const pristine = new Pristine(form, {
      classTo: 'form-group',
      errorClass: 'has-error',
      successClass: 'has-success',
      errorTextParent: 'form-group',
      errorTextTag: 'div',
      errorTextClass: 'error-text'
    });

    // 🔥 Disable native validation
    form.setAttribute("novalidate", "true");

    // ✅ Prevent form submission if validation fails
    form.addEventListener("submit", function (e) {
      console.log("🚀 Vérification du formulaire avant soumission...");
      let isValid = pristine.validate();

      if (!isValid) {
        console.warn("❌ Formulaire invalide, soumission bloquée !");
        e.preventDefault(); // ⛔ Block submission if invalid
      }
    });

    // ✅ Validate fields on input change for real-time feedback
    form.querySelectorAll("input, textarea, select").forEach(input => {
      input.addEventListener("input", function () {
        pristine.validate(input);
      });
    });

    // ✅ Validation rules for date fields
    const today = new Date().toISOString().split("T")[0]; // Get today's date in YYYY-MM-DD format

    const dateDebutInput = form.querySelector("#dateDeDebutPub");
    const dateFinInput = form.querySelector("#dateDeFinPub");

    if (dateDebutInput) {
      pristine.addValidator(dateDebutInput, function (value) {
        return value >= today; // Ensure start date is today or later
      }, "❌ La date de début ne peut pas être dans le passé.", 2, false);

      dateDebutInput.addEventListener("input", function () {
        pristine.validate(dateDebutInput);
        pristine.validate(dateFinInput); // Revalidate the end date too
      });
    }

    if (dateFinInput) {
      pristine.addValidator(dateFinInput, function (value) {
        const dateDebut = dateDebutInput.value;
        return !dateDebut || value > dateDebut; // Ensure end date is after start date
      }, "❌ La date de fin doit être postérieure à la date de début.", 2, false);

      dateFinInput.addEventListener("input", function () {
        pristine.validate(dateFinInput);
        pristine.validate(dateDebutInput);
      });
    }

    // ✅ Add validation rules for required fields (updated to include country)
    const requiredFields = ["nomOrganisme", "nom", "prenom", "titre", "description", "courriel", "LocaliteId", "categoriesId"];

    requiredFields.forEach(fieldId => {
      const input = form.querySelector(`#${fieldId}`);
      if (input) {
        pristine.addValidator(input, function (value) {
          return value.trim() !== ""; // Field must not be empty
        }, "Ce champ est requis.", 1, false);
      }
    });

    // ✅ Field-specific validation rules
    const fields = {
      adresse: {
        selector: "#adresse",
        validate: function (value) {
          return /^[0-9]+ [A-Za-zÀ-ÿ0-9\-,.' ]+$/.test(value);
        },
        message: "L'adresse doit commencer par un numéro suivi du nom de la rue."
      }
    };

    Object.values(fields).forEach(field => {
      const input = form.querySelector(field.selector);
      if (input) {
        console.log(`✅ Validation ajoutée : ${field.selector}`);
        pristine.addValidator(input, field.validate, field.message, 2, false);
      }
    });

    // ✅ Keep values after validation failure
    if (form.dataset.failedSubmission === "true") {
      pristine.validate();
    }

    // 🏙️ Auto-fill ville, mrc, and codePostal when a localité is selected
    const localiteSelect = form.querySelector("#localiteId");
    if (localiteSelect) {
      localiteSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const ville = selectedOption.getAttribute("data-ville") || "";
        const mrc = selectedOption.getAttribute("data-mrc") || "";
        const codePostal = selectedOption.getAttribute("data-prefixe") || "";

        const villeInput = document.getElementById("ville");
        const mrcInput = document.getElementById("mrc");
        const codePostalInput = document.getElementById("codePostal");

        if (villeInput) villeInput.value = ville;
        if (mrcInput) mrcInput.value = mrc;
        if (codePostalInput) codePostalInput.value = codePostal;
      });
    }


    // ✅ Initialize intl-tel-input for all telephone input fields
    const phoneInputs = document.querySelectorAll("input[type='tel']");
    phoneInputs.forEach(function (input) {
      const iti = window.intlTelInput(input, {
        initialCountry: "auto",
        autoPlaceholder: "aggressive",
        geoIpLookup: function (success, failure) {
          fetch("https://ipapi.co/json")
            .then(res => res.json())
            .then(data => success(data.country_code))
            .catch(failure);
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
      });
      // Update country based on dial code
      const countryData = window.intlTelInputGlobals.getCountryData();
      input.addEventListener("input", function () {
        const value = input.value.trim();
        if (value.startsWith("+")) {
          const numericPart = value.replace(/\D/g, "");
          let newCountry = null;
          for (const country of countryData) {
            if (numericPart.startsWith(country.dialCode)) {
              newCountry = country.iso2;
              break;
            }
          }
          if (newCountry && newCountry !== iti.getSelectedCountryData().iso2) {
            iti.setCountry(newCountry);
          }
        }
      });
      // Validate phone number on form submission
      input.closest("form")?.addEventListener("submit", function (event) {
        const phoneVal = input.value.trim();
        if (phoneVal !== "") {
          if (!iti.isValidNumber()) {
            event.preventDefault();
            document.getElementById("phone-error").classList.remove("hidden");
          } else {
            input.value = iti.getNumber();
          }
        }
      });
    });

  });

  // ✅ Function to toggle examples
  window.toggleExample = function (exampleId) {
    const exampleBox = document.getElementById(exampleId);
    exampleBox.classList.toggle("hidden");
  };
  
});

function initCarouselWhenReady() {
  const track = document.querySelector('.carousel-track');
  const slides = document.querySelectorAll('.carousel-slide');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');

  if (!track || slides.length <= 1 || !prevBtn || !nextBtn) {
    return setTimeout(initCarouselWhenReady, 200);
  }

  console.log("✅ Carousel initialized with looping");

  let currentIndex = 0;

  function updateCarousel() {
    const slideWidth = slides[0].clientWidth;
    track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
  }

  prevBtn.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateCarousel();
  });

  nextBtn.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % slides.length;
    updateCarousel();
  });

  window.addEventListener("resize", updateCarousel);
  updateCarousel();
}

window.addEventListener("load", initCarouselWhenReady);

