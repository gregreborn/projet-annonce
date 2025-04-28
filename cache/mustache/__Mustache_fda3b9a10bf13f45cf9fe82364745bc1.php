<?php

class __Mustache_fda3b9a10bf13f45cf9fe82364745bc1 extends Mustache_Template
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
        $buffer .= $indent . '  <!-- CropperJS CSS -->
';
        $buffer .= $indent . '  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '  <!-- CropperJS JS -->
';
        $buffer .= $indent . '  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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
        $buffer .= $indent . '  <!-- Plugin script + style -->
';
        $buffer .= $indent . '  <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/plugins/imageUploader/imageUploader.css">
';
        $buffer .= $indent . '  <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/plugins/mediaUploader/mediaUploader.css">
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/plugins/imageUploader/imageUploader.js"></script>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/plugins/mediaUploader/mediaUploader.js"></script>
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
        $buffer .= $indent . '  <script>
';
        $buffer .= $indent . '    document.addEventListener("DOMContentLoaded", function () {
';
        $buffer .= $indent . '      // --- ImageUploader (existant) ---
';
        $buffer .= $indent . '      if (typeof ImageUploader !== \'undefined\') {
';
        $buffer .= $indent . '        ImageUploader.init({
';
        $buffer .= $indent . '          uploadEndpoint: \'';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/endpoints/upload_endpoint.php\'
';
        $buffer .= $indent . '        });
';
        $buffer .= $indent . '        const btnImg = document.getElementById(\'open-image-uploader\');
';
        $buffer .= $indent . '        if (btnImg) {
';
        $buffer .= $indent . '          btnImg.addEventListener(\'click\', function () {
';
        $buffer .= $indent . '            $(\'#image-uploader-modal\').foundation(\'reveal\', \'open\');
';
        $buffer .= $indent . '          });
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '        // Optionnel : écouter un event custom si ImageUploader le dispatch
';
        $buffer .= $indent . '        document.addEventListener(\'imageUploader:uploadComplete\', function(e) {
';
        $buffer .= $indent . '          const imgs = e.detail; // tableau de {fileUrl,...}
';
        $buffer .= $indent . '          const preview = document.getElementById(\'image-preview-container\');
';
        $buffer .= $indent . '          preview.innerHTML = \'\';
';
        $buffer .= $indent . '          imgs.forEach(i => {
';
        $buffer .= $indent . '            const el = document.createElement(\'img\');
';
        $buffer .= $indent . '            el.src = i.fileUrl;
';
        $buffer .= $indent . '            el.style.maxWidth = \'100px\';
';
        $buffer .= $indent . '            el.style.marginRight = \'8px\';
';
        $buffer .= $indent . '            preview.appendChild(el);
';
        $buffer .= $indent . '          });
';
        $buffer .= $indent . '          document.getElementById(\'uploadedImages\').value = JSON.stringify(imgs);
';
        $buffer .= $indent . '        });
';
        $buffer .= $indent . '      }
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '      // --- MediaUploader (nouveau) ---
';
        $buffer .= $indent . '      if (typeof MediaUploader !== \'undefined\') {
';
        $buffer .= $indent . '        MediaUploader.init({
';
        $buffer .= $indent . '          uploadEndpoint: \'';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/plugins/mediaUploader/media_endpoint.php\'
';
        $buffer .= $indent . '        });
';
        $buffer .= $indent . '        const btnMedia = document.getElementById(\'open-media-uploader\');
';
        $buffer .= $indent . '        if (btnMedia) {
';
        $buffer .= $indent . '          btnMedia.addEventListener(\'click\', function () {
';
        $buffer .= $indent . '            $(\'#media-uploader-modal\').foundation(\'reveal\', \'open\');
';
        $buffer .= $indent . '          });
';
        $buffer .= $indent . '        }
';
        $buffer .= $indent . '        document.addEventListener(\'mediaUploader:uploadComplete\', function(e) {
';
        $buffer .= $indent . '          const medias = e.detail; // tableau de {fileUrl,...}
';
        $buffer .= $indent . '          const preview = document.getElementById(\'media-preview-container\');
';
        $buffer .= $indent . '          preview.innerHTML = \'\';
';
        $buffer .= $indent . '          medias.forEach(m => {
';
        $buffer .= $indent . '            const el = document.createElement(\'img\');
';
        $buffer .= $indent . '            el.src = m.fileUrl;
';
        $buffer .= $indent . '            el.style.maxWidth = \'100px\';
';
        $buffer .= $indent . '            el.style.marginRight = \'8px\';
';
        $buffer .= $indent . '            preview.appendChild(el);
';
        $buffer .= $indent . '          });
';
        $buffer .= $indent . '          document.getElementById(\'uploadedMedia\').value = JSON.stringify(medias);
';
        $buffer .= $indent . '        });
';
        $buffer .= $indent . '      }
';
        $buffer .= $indent . '    });
';
        $buffer .= $indent . '  </script>
';
        $buffer .= $indent . '  
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
