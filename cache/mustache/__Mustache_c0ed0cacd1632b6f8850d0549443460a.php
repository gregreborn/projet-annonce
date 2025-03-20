<?php

class __Mustache_c0ed0cacd1632b6f8850d0549443460a extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Barre supérieure visible uniquement en mobile (hide-for-large) -->
';
        $buffer .= $indent . '<div class="title-bar hide-for-large">
';
        $buffer .= $indent . '  <div class="title-bar-left">
';
        $buffer .= $indent . '    <!-- Bouton hamburger (menu-icon) -->
';
        $buffer .= $indent . '    <button class="menu-icon" type="button" data-toggle="offCanvas"></button>
';
        $buffer .= $indent . '    <span class="title-bar-title">Gregory</span>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Sidebar off-canvas, toujours visible en grand écran (reveal-for-large) -->
';
        $buffer .= $indent . '<div class="off-canvas position-left reveal-for-large" id="offCanvas" data-off-canvas>
';
        $buffer .= $indent . '  <!-- Logo ou titre -->
';
        $buffer .= $indent . '  <div class="logo text-center">
';
        $buffer .= $indent . '    <h2>Gregory</h2>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Navigation verticale -->
';
        $buffer .= $indent . '  <ul class="vertical menu">
';
        $buffer .= $indent . '    <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/accueil">Accueil</a></li>
';
        $buffer .= $indent . '    <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/annonces">Annonces</a></li>
';
        $buffer .= $indent . '    <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/choix_annonce">Publier une annonce</a></li>
';
        $buffer .= $indent . '    <!-- Vous pouvez ajouter d\'autres liens, ex: “J\'offre”, “J\'ai besoin” -->
';
        $buffer .= $indent . '  </ul>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }
}
