<?php

class __Mustache_c8816c17c7950c5da4c9c48677b8790c extends Mustache_Template
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
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Foundation CSS & your custom styles -->
';
        $buffer .= $indent . '  <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/libs/foundation/css/foundation.min.css">
';
        $buffer .= $indent . '  <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/css/base.css">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Fonts & Plugins -->
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
        $buffer .= $indent . '  <!-- Off-canvas layout -->
';
        $buffer .= $indent . '  <div class="off-canvas-wrap" data-offcanvas>
';
        $buffer .= $indent . '    <div class="inner-wrap">
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Mobile Hamburger Header -->
';
        $buffer .= $indent . '      <nav class="tab-bar show-for-small-only">
';
        $buffer .= $indent . '        <section class="left-small">
';
        $buffer .= $indent . '          <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
';
        $buffer .= $indent . '        </section>
';
        $buffer .= $indent . '        <section class="middle tab-bar-section">
';
        $buffer .= $indent . '          <h1 class="title">MarketPlace</h1>
';
        $buffer .= $indent . '        </section>
';
        $buffer .= $indent . '      </nav>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Desktop Header -->
';
        $buffer .= $indent . '      <header class="site-header show-for-medium-up">
';
        $buffer .= $indent . '        <div class="row">
';
        $buffer .= $indent . '          <div class="large-12 columns">
';
        $buffer .= $indent . '            <h1 class="logo">MarketPlace</h1>
';
        $buffer .= $indent . '          </div>
';
        $buffer .= $indent . '        </div>
';
        $buffer .= $indent . '      </header>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Sidebar Menu (Off-canvas on small, static on large with optional CSS) -->
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
        $buffer .= $indent . '      <section class="main-section">
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('content'), $context, $indent);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '      </section>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      <!-- Footer -->
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
        $buffer .= $indent . '      <!-- Exit overlay for mobile -->
';
        $buffer .= $indent . '      <a class="exit-off-canvas"></a>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Required Scripts -->
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
        $buffer .= $indent . '  <script>$(document).foundation();</script>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Your scripts -->
';
        $buffer .= $indent . '  <script src="https://unpkg.com/pristinejs/dist/pristine.min.js"></script>
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
}
