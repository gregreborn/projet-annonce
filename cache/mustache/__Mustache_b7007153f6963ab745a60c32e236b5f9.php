<?php

class __Mustache_b7007153f6963ab745a60c32e236b5f9 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<div class="site-header">
';
        $buffer .= $indent . '    <div class="logo">
';
        $buffer .= $indent . '      <h1>Gregory</h1>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '    <!-- Bouton hamburger (caché en desktop, visible en mobile) -->
';
        $buffer .= $indent . '    <button class="hamburger" id="hamburgerBtn" aria-label="Ouvrir le menu" aria-expanded="false">
';
        $buffer .= $indent . '      <span class="hamburger-icon"></span>
';
        $buffer .= $indent . '    </button>
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '    <nav class="main-nav" id="mainNav">
';
        $buffer .= $indent . '      <ul>
';
        $buffer .= $indent . '        <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/accueil">Accueil</a></li>
';
        $buffer .= $indent . '        <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/annonces">Annonces</a></li>
';
        $buffer .= $indent . '        <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/choix_annonce">Publier une annonce</a></li>
';
        $buffer .= $indent . '      </ul>
';
        $buffer .= $indent . '    </nav>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '  <script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/assets/js/entete.js"></script>
';
        $buffer .= $indent . '  ';

        return $buffer;
    }
}
