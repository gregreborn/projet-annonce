<?php

class __Mustache_89eb928a1408b2ea8faccc9e254df960 extends Mustache_Template
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
        $buffer .= $indent . '  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600&display=swap" rel="stylesheet">
';
        $buffer .= $indent . '  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '<body>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Off-canvas wrapper -->
';
        $buffer .= $indent . '  <div class="off-canvas-wrap" data-offcanvas>
';
        $buffer .= $indent . '    <div class="inner-wrap">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Overlay -->
';
        $buffer .= $indent . '      <div class="off-canvas-overlay"></div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Fixed header partial -->
';
        if ($partial = $this->mustache->loadPartial('entete')) {
            $buffer .= $partial->renderInternal($context, $indent . '      ');
        }
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Sidebar -->
';
        $buffer .= $indent . '      <aside class="left-off-canvas-menu">
';
        $buffer .= $indent . '        <ul class="off-canvas-list">
';
        if ($partial = $this->mustache->loadPartial('nav_items')) {
            $buffer .= $partial->renderInternal($context, $indent . '          ');
        }
        $buffer .= $indent . '        </ul>
';
        $buffer .= $indent . '      </aside>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Main content -->
';
        $buffer .= $indent . '      <main class="grid-container main-section">
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('content'), $context, $indent);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '      </main>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <footer>
';
        if ($partial = $this->mustache->loadPartial('footer')) {
            $buffer .= $partial->renderInternal($context, $indent . '        ');
        }
        $buffer .= $indent . '      </footer>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Overlay Exit (optional) -->
';
        $buffer .= $indent . '      <a class="exit-off-canvas show-for-medium-down"></a>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Scripts -->
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
        $buffer .= $indent . '  <script>
';
        $buffer .= $indent . '    $(document).ready(function () {
';
        $buffer .= $indent . '      $(document).foundation();
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '      function adjustSidebar() {
';
        $buffer .= $indent . '        const $wrap = $(\'.off-canvas-wrap\');
';
        $buffer .= $indent . '        if (window.innerWidth >= 1024) {
';
        $buffer .= $indent . '          $wrap.addClass(\'move-right\');
';
        $buffer .= $indent . '          $(\'.off-canvas-overlay\').hide();
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '          $wrap.removeClass(\'move-right\');
';
        $buffer .= $indent . '          $(\'.off-canvas-overlay\').hide();
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '      }
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '      // Toggle on hamburger click
';
        $buffer .= $indent . '      $(\'.left-off-canvas-toggle\').on(\'click\', function (e) {
';
        $buffer .= $indent . '        e.preventDefault();
';
        $buffer .= $indent . '        const $wrap = $(\'.off-canvas-wrap\');
';
        $buffer .= $indent . '        $wrap.toggleClass(\'move-right\');
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '        // Show/hide overlay only on mobile/tablet
';
        $buffer .= $indent . '        if (window.innerWidth < 1024) {
';
        $buffer .= $indent . '          $(\'.off-canvas-overlay\').toggle();
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '      });
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '      // Close sidebar on nav click (mobile only)
';
        $buffer .= $indent . '      $(\'.off-canvas-list a\').on(\'click\', function () {
';
        $buffer .= $indent . '        if (window.innerWidth < 1024) {
';
        $buffer .= $indent . '          $(\'.off-canvas-wrap\').removeClass(\'move-right\');
';
        $buffer .= $indent . '          $(\'.off-canvas-overlay\').hide();
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '      });
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '      // Close sidebar if overlay clicked
';
        $buffer .= $indent . '      $(\'.off-canvas-overlay\').on(\'click\', function () {
';
        $buffer .= $indent . '        $(\'.off-canvas-wrap\').removeClass(\'move-right\');
';
        $buffer .= $indent . '        $(this).hide();
';
        $buffer .= $indent . '      });
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '      adjustSidebar();
';
        $buffer .= $indent . '      $(window).on(\'resize\', adjustSidebar);
';
        $buffer .= $indent . '    });
';
        $buffer .= $indent . '  </script>  
';
        $buffer .= $indent . '  <script src="https://unpkg.com/pristinejs/dist/pristine.min.js"></script>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/base.js"></script>
';
        $buffer .= $indent . '  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
';
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>
';

        return $buffer;
    }
}
