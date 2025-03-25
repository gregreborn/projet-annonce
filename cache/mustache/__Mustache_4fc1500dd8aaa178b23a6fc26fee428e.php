<?php

class __Mustache_4fc1500dd8aaa178b23a6fc26fee428e extends Mustache_Template
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
        $buffer .= $indent . '  <!-- Foundation CSS & Custom Styles -->
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
        $buffer .= $indent . '  <!-- Responsive Nav (Top Bar for small/tablet, Sidebar for desktop) -->
';
        $buffer .= $indent . '  <nav class="top-bar" data-topbar role="navigation">
';
        $buffer .= $indent . '    <ul class="title-area">
';
        $buffer .= $indent . '      <li class="name">
';
        $buffer .= $indent . '        <h1><a href="#">MarketPlace</a></h1>
';
        $buffer .= $indent . '      </li>
';
        $buffer .= $indent . '      <li class="toggle-topbar menu-icon">
';
        $buffer .= $indent . '        <a href="#"><span></span></a>
';
        $buffer .= $indent . '      </li>
';
        $buffer .= $indent . '    </ul>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <section class="top-bar-section show-for-medium-down">
';
        $buffer .= $indent . '      <ul class="right">
';
        if ($partial = $this->mustache->loadPartial('nav_items')) {
            $buffer .= $partial->renderInternal($context, $indent . '        ');
        }
        $buffer .= $indent . '      </ul>
';
        $buffer .= $indent . '    </section>
';
        $buffer .= $indent . '  </nav>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <div class="row">
';
        $buffer .= $indent . '    <!-- Static Sidebar on Desktop -->
';
        $buffer .= $indent . '    <aside class="large-3 columns show-for-large-up">
';
        $buffer .= $indent . '      <ul class="off-canvas-list">
';
        if ($partial = $this->mustache->loadPartial('nav_items')) {
            $buffer .= $partial->renderInternal($context, $indent . '        ');
        }
        $buffer .= $indent . '      </ul>
';
        $buffer .= $indent . '    </aside>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '    <!-- Main content area -->
';
        $buffer .= $indent . '    <section class="large-9 medium-12 small-12 columns main-section">
';
        $buffer .= $indent . '      ';
        $value = $this->resolveValue($context->find('content'), $context, $indent);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    </section>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Footer -->
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
        $buffer .= $indent . '  <script>$(document).foundation();</script>
';
        $buffer .= $indent . '
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
