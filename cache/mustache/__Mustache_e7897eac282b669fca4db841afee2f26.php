<?php

class __Mustache_e7897eac282b669fca4db841afee2f26 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Barre supérieure visible uniquement en mobile -->
';
        $buffer .= $indent . '<div class="title-bar hide-for-large">
';
        $buffer .= $indent . '  <div class="title-bar-left">
';
        $buffer .= $indent . '    <!-- Bouton hamburger Foundation -->
';
        $buffer .= $indent . '    <button class="menu-icon" type="button" data-toggle="offCanvas"></button>
';
        $buffer .= $indent . '    <!-- Nom du site -->
';
        $buffer .= $indent . '    <span class="title-bar-title">Gregory</span>
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
        $buffer .= $indent . '  <!-- Logo ou titre dans la sidebar (visible en desktop, ou même en mobile si on ouvre le menu) -->
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
        $buffer .= $indent . '    <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/offre">J’offre</a></li>
';
        $buffer .= $indent . '    <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/besoin">J’ai besoin</a></li>
';
        $buffer .= $indent . '  </ul>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }
}
