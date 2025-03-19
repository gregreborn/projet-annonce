<?php

class __Mustache_bfc097cfc1f1cf0006406a81913adbce extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!DOCTYPE html>
';
        $buffer .= $indent . '<html lang="fr">
';
        $buffer .= $indent . '<head>
';
        $buffer .= $indent . '    <meta charset="UTF-8">
';
        $buffer .= $indent . '    <meta name="viewport" content="width=device-width, initial-scale=1.0">
';
        $buffer .= $indent . '    <title>';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '</title>
';
        $buffer .= $indent . '    <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/css/base.css">
';
        $buffer .= $indent . '    <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/foundation/css/foundation.min.css">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <!-- Intl-Tel-Input Styles -->
';
        $buffer .= $indent . '    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '<body>
';
        $buffer .= $indent . '    <header>
';
        if ($partial = $this->mustache->loadPartial('entete')) {
            $buffer .= $partial->renderInternal($context, $indent . '        ');
        }
        $buffer .= $indent . '    </header>
';
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    <main class="grid-container">
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('content'), $context, $indent);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    </main>
';
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    <footer>
';
        if ($partial = $this->mustache->loadPartial('footer')) {
            $buffer .= $partial->renderInternal($context, $indent . '        ');
        }
        $buffer .= $indent . '    </footer>
';
        $buffer .= $indent . '    
';
        if ($partial = $this->mustache->loadPartial('message')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/jquery.min.js"></script>
';
        $buffer .= $indent . '    <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/foundation/js/foundation.min.js"></script>
';
        $buffer .= $indent . '    <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
';
        $buffer .= $indent . '    <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/jquery.scrollTo.js"></script>
';
        $buffer .= $indent . '    <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/base.js"></script>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <!-- Intl-Tel-Input Styles -->
';
        $buffer .= $indent . '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
';
        $buffer .= $indent . '<script>
';
        $buffer .= $indent . '  document.addEventListener("DOMContentLoaded", function() {
';
        $buffer .= $indent . '    const phoneInputs = document.querySelectorAll("input[type=\'tel\']");
';
        $buffer .= $indent . '    phoneInputs.forEach(function(input) {
';
        $buffer .= $indent . '      const iti = window.intlTelInput(input, {
';
        $buffer .= $indent . '        initialCountry: "auto",
';
        $buffer .= $indent . '        geoIpLookup: function(success, failure) {
';
        $buffer .= $indent . '          fetch("https://ipapi.co/json")
';
        $buffer .= $indent . '            .then(res => res.json())
';
        $buffer .= $indent . '            .then(data => success(data.country_code))
';
        $buffer .= $indent . '            .catch(failure);
';
        $buffer .= $indent . '        },
';
        $buffer .= $indent . '        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
';
        $buffer .= $indent . '      });
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      input.closest("form")?.addEventListener("submit", function(event) {
';
        $buffer .= $indent . '        if (!iti.isValidNumber()) {
';
        $buffer .= $indent . '          event.preventDefault();
';
        $buffer .= $indent . '          document.getElementById("phoneError").classList.remove("hidden");
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '          input.value = iti.getNumber(); // Convert to E.164 format before submission
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '      });
';
        $buffer .= $indent . '    });
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    // Example toggle functionality (applies globally)
';
        $buffer .= $indent . '    document.querySelectorAll(".example-btn").forEach(button => {
';
        $buffer .= $indent . '      button.addEventListener("click", function() {
';
        $buffer .= $indent . '        const target = document.getElementById(this.dataset.target);
';
        $buffer .= $indent . '        target.classList.toggle("hidden");
';
        $buffer .= $indent . '        this.textContent = target.classList.contains("hidden") ? "📖 Voir un exemple" : "❌ Masquer l\'exemple";
';
        $buffer .= $indent . '      });
';
        $buffer .= $indent . '    });
';
        $buffer .= $indent . '  });
';
        $buffer .= $indent . '</script>
';
        $buffer .= $indent . '<style>
';
        $buffer .= $indent . '  .hidden { display: none; }
';
        $buffer .= $indent . '  .example-btn {
';
        $buffer .= $indent . '    background: none;
';
        $buffer .= $indent . '    border: none;
';
        $buffer .= $indent . '    color: blue;
';
        $buffer .= $indent . '    text-decoration: underline;
';
        $buffer .= $indent . '    cursor: pointer;
';
        $buffer .= $indent . '    font-size: 0.9em;
';
        $buffer .= $indent . '    padding: 0;
';
        $buffer .= $indent . '    margin-left: 10px;
';
        $buffer .= $indent . '  }
';
        $buffer .= $indent . '  .error-message {
';
        $buffer .= $indent . '    color: red;
';
        $buffer .= $indent . '    font-size: 0.9em;
';
        $buffer .= $indent . '    margin-top: 5px;
';
        $buffer .= $indent . '  }
';
        $buffer .= $indent . '</style>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Display phone validation error -->
';
        $buffer .= $indent . '<p id="phoneError" class="error-message hidden">❌ Numéro de téléphone invalide.</p>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>
';

        return $buffer;
    }
}
