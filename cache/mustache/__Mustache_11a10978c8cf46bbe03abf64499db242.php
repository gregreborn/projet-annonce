<?php

class __Mustache_11a10978c8cf46bbe03abf64499db242 extends Mustache_Template
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
        $buffer .= $indent . '  <meta charset="UTF-8">
';
        $buffer .= $indent . '  <meta name="viewport" content="width=device-width, initial-scale=1.0">
';
        $buffer .= $indent . '  <title>';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '</title>
';
        $buffer .= $indent . '  <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/css/base.css">
';
        $buffer .= $indent . '  <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/foundation/css/foundation.min.css">
';
        $buffer .= $indent . '  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '<body>
';
        $buffer .= $indent . '  <header>
';
        if ($partial = $this->mustache->loadPartial('entete')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '  </header>
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '  <main class="grid-container">
';
        $buffer .= $indent . '    ';
        $value = $this->resolveValue($context->find('content'), $context, $indent);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '  </main>
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '  <footer>
';
        if ($partial = $this->mustache->loadPartial('footer')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '  </footer>
';
        $buffer .= $indent . '  
';
        if ($partial = $this->mustache->loadPartial('message')) {
            $buffer .= $partial->renderInternal($context, $indent . '  ');
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <p id="phoneError" class="error-message hidden">❌ Numéro de téléphone invalide.</p>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/jquery.min.js"></script>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/foundation/js/foundation.min.js"></script>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/jquery.scrollTo.js"></script>
';
        $buffer .= $indent . '  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/base.js"></script>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/form-validation.js"></script>
';
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>
';

        return $buffer;
    }
}
