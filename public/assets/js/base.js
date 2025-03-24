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
    const requiredFields = ["nomOrganisme", "nom", "prenom", "titre", "description", "courriel", "country", "ville", "province", "codePostal", "categoriesId"];

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
      codePostal: {
        selector: "#codePostal",
        validate: function (value) {
          const caPattern = /^[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]\d$/; // Canada
          const usPattern = /^\d{5}(-\d{4})?$/; // USA
          return caPattern.test(value) || usPattern.test(value);
        },
        message: "Code postal invalide : utilisez le format canadien (A1A 1A1) ou américain (12345-6789)."
      },
      province: {
        selector: "#province",
        validate: function (value) {
          const canadian = ["AB", "BC", "MB", "NB", "NL", "NS", "NT", "NU", "ON", "PE", "QC", "SK", "YT"];
          const us = ["AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY"];
          return canadian.includes(value.toUpperCase()) || us.includes(value.toUpperCase());
        },
        message: "Province/État invalide. Choisissez une option valide."
      },
      ville: {
        selector: "#ville",
        validate: function (value) {
          return /^[A-Za-zÀ-ÿ' -]+$/.test(value);
        },
        message: "La ville ne doit contenir que des lettres et des espaces."
      },
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
