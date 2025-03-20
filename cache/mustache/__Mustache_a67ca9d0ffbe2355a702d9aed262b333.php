<?php

class __Mustache_a67ca9d0ffbe2355a702d9aed262b333 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<div class="sidebar" id="sidebar">
';
        $buffer .= $indent . '    <!-- Logo en haut -->
';
        $buffer .= $indent . '    <div class="sidebar-header">
';
        $buffer .= $indent . '      <div class="logo">
';
        $buffer .= $indent . '        <h1>Gregory</h1>
';
        $buffer .= $indent . '      </div>
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '      <!-- Bouton hamburger visible en mobile -->
';
        $buffer .= $indent . '      <button class="hamburger" id="hamburgerBtn" aria-label="Ouvrir le menu" aria-expanded="false">
';
        $buffer .= $indent . '        <span class="hamburger-icon"></span>
';
        $buffer .= $indent . '      </button>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '    <!-- Navigation latérale -->
';
        $buffer .= $indent . '    <nav class="sidebar-nav" id="mainNav">
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
        $buffer .= $indent . '        <!-- Exemples de liens “J’offre” / “J’ai besoin” -->
';
        $buffer .= $indent . '        <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/offre">J’offre</a></li>
';
        $buffer .= $indent . '        <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/besoin">J’ai besoin</a></li>
';
        $buffer .= $indent . '      </ul>
';
        $buffer .= $indent . '    </nav>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '  
';
        $buffer .= $indent . '  <!-- Inclure le script de gestion du hamburger après le HTML -->
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
