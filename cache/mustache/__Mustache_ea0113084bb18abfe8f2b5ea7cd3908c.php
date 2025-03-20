<?php

class __Mustache_ea0113084bb18abfe8f2b5ea7cd3908c extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Barre supérieure (top-bar) -->
';
        $buffer .= $indent . '<div class="top-bar">
';
        $buffer .= $indent . '  <div class="top-bar-left">
';
        $buffer .= $indent . '    <ul class="menu">
';
        $buffer .= $indent . '      <!-- Nom du site ou logo -->
';
        $buffer .= $indent . '      <li class="menu-text">Gregory</li>
';
        $buffer .= $indent . '    </ul>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '  <!-- Menu horizontal en grand écran -->
';
        $buffer .= $indent . '  <div class="top-bar-right show-for-large">
';
        $buffer .= $indent . '    <ul class="menu">
';
        $buffer .= $indent . '      <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/accueil">Accueil</a></li>
';
        $buffer .= $indent . '      <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/annonces">Annonces</a></li>
';
        $buffer .= $indent . '      <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/choix_annonce">Publier une annonce</a></li>
';
        $buffer .= $indent . '    </ul>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '  <!-- Bouton hamburger en mobile -->
';
        $buffer .= $indent . '  <div class="top-bar-right hide-for-large">
';
        $buffer .= $indent . '    <button class="menu-icon" type="button" data-toggle="offCanvas"></button>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Menu latéral Off-canvas -->
';
        $buffer .= $indent . '<div class="off-canvas position-left reveal-for-large" id="offCanvas" data-off-canvas>
';
        $buffer .= $indent . '  <!-- Logo ou titre dans la sidebar -->
';
        $buffer .= $indent . '  <div class="logo text-center">
';
        $buffer .= $indent . '    <h2>Gregory</h2>
';
        $buffer .= $indent . '  </div>
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
        $buffer .= $indent . '    <!-- Vous pouvez ajouter d’autres liens, ex: “J’offre”, “J’ai besoin”, etc. -->
';
        $buffer .= $indent . '  </ul>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }
}
