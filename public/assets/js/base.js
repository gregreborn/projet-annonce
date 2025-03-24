document.addEventListener("DOMContentLoaded", function () {
  console.log("🔍 PristineJS chargé ?", typeof Pristine !== "undefined");

  const forms = document.querySelectorAll("form");

  forms.forEach(form => {
    console.log("📌 Formulaire détecté :", form);

    if (typeof Pristine === "undefined") {
      console.error("❌ PristineJS n'est pas chargé !");
      return;
    }

    // Initialisation de PristineJS pour la validation du formulaire
    const pristine = new Pristine(form, {
      classTo: 'form-group',
      errorClass: 'has-error',
      successClass: 'has-success',
      errorTextParent: 'form-group',
      errorTextTag: 'div',
      errorTextClass: 'error-text'
    });

    form.setAttribute("novalidate", "true"); // Désactive la validation HTML native

    // Validation lors de la soumission du formulaire
    form.addEventListener("submit", function (e) {
      console.log("🚀 Vérification du formulaire avant soumission...");
      let isValid = pristine.validate();

      if (!isValid) {
        console.warn("❌ Formulaire invalide, soumission bloquée !");
        e.preventDefault(); // Empêche l'envoi si le formulaire est invalide
      }
    });

    // Validation en temps réel sur les champs
    form.querySelectorAll("input, textarea, select").forEach(input => {
      input.addEventListener("input", function () {
        pristine.validate(input);
      });
    });

    // Règles spécifiques pour les dates de début et de fin
    const today = new Date().toISOString().split("T")[0]; // Get today's date in YYYY-MM-DD format

    const dateDebutInput = form.querySelector("#dateDeDebutPub");
    const dateFinInput = form.querySelector("#dateDeFinPub");

    if (dateDebutInput) {
      pristine.addValidator(dateDebutInput, function (value) {
        return value >= today; 
      }, "❌ La date de début ne peut pas être dans le passé.", 2, false);

      dateDebutInput.addEventListener("input", function () {
        pristine.validate(dateDebutInput);
        pristine.validate(dateFinInput); 
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

    // Champs obligatoires à valider
    const requiredFields = ["nomOrganisme", "nom", "prenom", "titre", "description", "courriel", "country", "ville", "province", "codePostal", "categoriesId"];

    requiredFields.forEach(fieldId => {
      const input = form.querySelector(`#${fieldId}`);
      if (input) {
        pristine.addValidator(input, function (value) {
          return value.trim() !== "";
        }, "Ce champ est requis.", 1, false);
      }
    });

    // Règles de validation personnalisées
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

    // Revalide automatiquement si un précédent échec de soumission est détecté
    if (form.dataset.failedSubmission === "true") {
      pristine.validate();
    }

    // Initialisation de la bibliothèque intl-tel-input pour les champs téléphone
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
      input.closest("form")?.addEventListener("submit", function (event) {
        const phoneVal = input.value.trim();
        if (phoneVal !== "") {
          if (!iti.isValidNumber()) {
            event.preventDefault();
            document.getElementById("phone-error").classList.remove("hidden");
          } else {
            input.value = iti.getNumber();// Remplace par la version internationale
          }
        }
      });
    });

    // Mise à jour dynamique de la liste des provinces selon le pays sélectionné
    const countrySelect = form.querySelector("#country");
    const provinceSelect = form.querySelector("#province");
    if (countrySelect && provinceSelect) {
      const provincesByCountry = {
        "CA": [
          { value: "AB", label: "Alberta" },
          { value: "BC", label: "British Columbia" },
          { value: "MB", label: "Manitoba" },
          { value: "NB", label: "New Brunswick" },
          { value: "NL", label: "Newfoundland and Labrador" },
          { value: "NS", label: "Nova Scotia" },
          { value: "NT", label: "Northwest Territories" },
          { value: "NU", label: "Nunavut" },
          { value: "ON", label: "Ontario" },
          { value: "PE", label: "Prince Edward Island" },
          { value: "QC", label: "Quebec" },
          { value: "SK", label: "Saskatchewan" },
          { value: "YT", label: "Yukon" }
        ],
        "US": [
          { value: "AL", label: "Alabama" },
          { value: "AK", label: "Alaska" },
          { value: "AZ", label: "Arizona" },
          { value: "AR", label: "Arkansas" },
          { value: "CA", label: "California" },
          { value: "CO", label: "Colorado" },
          { value: "CT", label: "Connecticut" },
          { value: "DE", label: "Delaware" },
          { value: "FL", label: "Florida" },
          { value: "GA", label: "Georgia" },
          { value: "HI", label: "Hawaii" },
          { value: "ID", label: "Idaho" },
          { value: "IL", label: "Illinois" },
          { value: "IN", label: "Indiana" },
          { value: "IA", label: "Iowa" },
          { value: "KS", label: "Kansas" },
          { value: "KY", label: "Kentucky" },
          { value: "LA", label: "Louisiana" },
          { value: "ME", label: "Maine" },
          { value: "MD", label: "Maryland" },
          { value: "MA", label: "Massachusetts" },
          { value: "MI", label: "Michigan" },
          { value: "MN", label: "Minnesota" },
          { value: "MS", label: "Mississippi" },
          { value: "MO", label: "Missouri" },
          { value: "MT", label: "Montana" },
          { value: "NE", label: "Nebraska" },
          { value: "NV", label: "Nevada" },
          { value: "NH", label: "New Hampshire" },
          { value: "NJ", label: "New Jersey" },
          { value: "NM", label: "New Mexico" },
          { value: "NY", label: "New York" },
          { value: "NC", label: "North Carolina" },
          { value: "ND", label: "North Dakota" },
          { value: "OH", label: "Ohio" },
          { value: "OK", label: "Oklahoma" },
          { value: "OR", label: "Oregon" },
          { value: "PA", label: "Pennsylvania" },
          { value: "RI", label: "Rhode Island" },
          { value: "SC", label: "South Carolina" },
          { value: "SD", label: "South Dakota" },
          { value: "TN", label: "Tennessee" },
          { value: "TX", label: "Texas" },
          { value: "UT", label: "Utah" },
          { value: "VT", label: "Vermont" },
          { value: "VA", label: "Virginia" },
          { value: "WA", label: "Washington" },
          { value: "WV", label: "West Virginia" },
          { value: "WI", label: "Wisconsin" },
          { value: "WY", label: "Wyoming" }
        ]
      };

      function updateProvinceOptions(countryCode) {
        const provinces = provincesByCountry[countryCode] || [];
        provinceSelect.innerHTML = "";
        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "-- Sélectionner une province/state --";
        provinceSelect.appendChild(defaultOption);
        provinces.forEach(item => {
          const option = document.createElement("option");
          option.value = item.value;
          option.textContent = item.label;
          provinceSelect.appendChild(option);
        });
      }

      // Initialisation avec la valeur actuelle du champ pays
      updateProvinceOptions(countrySelect.value || "CA");

      countrySelect.addEventListener("change", function () {
        updateProvinceOptions(countrySelect.value);
      });
    }
  });

  /**
   * Affiche ou masque une boîte d’exemple selon l’ID fourni
   */  
  window.toggleExample = function (exampleId) {
    const exampleBox = document.getElementById(exampleId);
    exampleBox.classList.toggle("hidden");
  };
});
