<?php

class __Mustache_724f13725c90dc628688e970c61049ad extends Mustache_Template
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
        $buffer .= $indent . '  <style>
';
        $buffer .= $indent . '    html, body {
';
        $buffer .= $indent . '      overflow-x: hidden;
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    .menu-icon {
';
        $buffer .= $indent . '      display: block;
';
        $buffer .= $indent . '      position: fixed;
';
        $buffer .= $indent . '      top: 20px;
';
        $buffer .= $indent . '      left: 20px;
';
        $buffer .= $indent . '      font-size: 1.5rem;
';
        $buffer .= $indent . '      z-index: 1101;
';
        $buffer .= $indent . '      color: var(--accent);
';
        $buffer .= $indent . '      cursor: pointer;
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    @media screen and (min-width: 1024px) {
';
        $buffer .= $indent . '      .menu-icon {
';
        $buffer .= $indent . '        display: none;
';
        $buffer .= $indent . '      }
';
        $buffer .= $indent . '    }
';
        $buffer .= $indent . '  </style>
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '<body>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Hamburger Icon -->
';
        $buffer .= $indent . '  <a href="#" class="menu-icon show-for-medium-down" id="hamburger">&#9776;</a>
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
        $buffer .= $indent . '      // Toggle sidebar manually
';
        $buffer .= $indent . '      $(\'#hamburger\').on(\'click\', function (e) {
';
        $buffer .= $indent . '        e.preventDefault();
';
        $buffer .= $indent . '        $(\'.off-canvas-wrap\').toggleClass(\'move-right\');
';
        $buffer .= $indent . '      });
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      // Force sidebar visible on large screens
';
        $buffer .= $indent . '      function adjustSidebar() {
';
        $buffer .= $indent . '        if (window.innerWidth >= 1024) {
';
        $buffer .= $indent . '          $(\'.off-canvas-wrap\').addClass(\'move-right\');
';
        $buffer .= $indent . '        } else {
';
        $buffer .= $indent . '          $(\'.off-canvas-wrap\').removeClass(\'move-right\');
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '      }
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
        $buffer .= $indent . '
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
