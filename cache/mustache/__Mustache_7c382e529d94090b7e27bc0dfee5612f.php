<?php

class __Mustache_7c382e529d94090b7e27bc0dfee5612f extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
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
        $buffer .= $indent . '  <script src="https://cdn.jsdelivr.net/npm/pristinejs/dist/pristine.min.js"></script>
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '<body>
';
        // 'flashMessage' section
        $value = $context->find('flashMessage');
        $buffer .= $this->section11e75cda3dc135eab579f9501d2eeb81($context, $indent, $value);
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
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>
';

        return $buffer;
    }

    private function section9f4373b7d66ca8b3d98c14680325a2bd(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = 'error';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= 'error';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section11e75cda3dc135eab579f9501d2eeb81(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
  <div class="flash-message {{#flashError}}error{{/flashError}}">
    {{{flashMessage}}}
  </div>
';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '  <div class="flash-message ';
                // 'flashError' section
                $value = $context->find('flashError');
                $buffer .= $this->section9f4373b7d66ca8b3d98c14680325a2bd($context, $indent, $value);
                $buffer .= '">
';
                $buffer .= $indent . '    ';
                $value = $this->resolveValue($context->find('flashMessage'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '  </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}
