<?php

class __Mustache_4d49162b60fdbe1a4906ec16ea6e2ce4 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Title-Bar visible en mobile -->
';
        $buffer .= $indent . '<div class="title-bar" data-responsive-toggle="offCanvas" data-hide-for="medium">
';
        $buffer .= $indent . '  <div class="title-bar-left">
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
        $buffer .= $indent . '<!-- Off-Canvas sidebar (menu latéral) -->
';
        $buffer .= $indent . '<div class="off-canvas position-left" id="offCanvas" data-off-canvas>
';
        $buffer .= $indent . '  <!-- Bouton de fermeture, optionnel -->
';
        $buffer .= $indent . '  <button class="close-button" aria-label="Fermer le menu" type="button" data-close>
';
        $buffer .= $indent . '    <span aria-hidden="true">&times;</span>
';
        $buffer .= $indent . '  </button>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '  <!-- Logo dans la sidebar -->
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
        $buffer .= $indent . '    <!-- Ajoutez ici d\'autres liens si besoin -->
';
        $buffer .= $indent . '  </ul>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }
}
