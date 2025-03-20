<?php

class __Mustache_5a618b1ff8b673deecbccbff03be38fb extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Top-Bar (toujours visible) -->
';
        $buffer .= $indent . '<div class="top-bar">
';
        $buffer .= $indent . '  <div class="top-bar-left">
';
        $buffer .= $indent . '    <ul class="menu">
';
        $buffer .= $indent . '      <!-- Logo ou Nom du Site -->
';
        $buffer .= $indent . '      <li class="menu-text">Gregory</li>
';
        $buffer .= $indent . '    </ul>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '  <div class="top-bar-right show-for-large">
';
        $buffer .= $indent . '    <!-- Menu horizontal en desktop (optionnel) -->
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
        $buffer .= $indent . '  <div class="top-bar-right hide-for-large">
';
        $buffer .= $indent . '    <!-- Bouton hamburger en mobile -->
';
        $buffer .= $indent . '    <button class="menu-icon" type="button" data-toggle="offCanvas"></button>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Off-Canvas sidebar (menu latéral) -->
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
        $buffer .= $indent . '  </ul>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }
}
