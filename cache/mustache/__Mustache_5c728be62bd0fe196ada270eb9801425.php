<?php

class __Mustache_5c728be62bd0fe196ada270eb9801425 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Navigation en mobile (tab-bar) : cachée en grand écran -->
';
        $buffer .= $indent . '<nav class="tab-bar hide-for-large-up">
';
        $buffer .= $indent . '  <!-- Bouton hamburger à gauche -->
';
        $buffer .= $indent . '  <section class="left-small">
';
        $buffer .= $indent . '    <a class="left-off-canvas-toggle menu-icon" href="#">Menu</a>
';
        $buffer .= $indent . '  </section>
';
        $buffer .= $indent . '  <!-- Titre au milieu -->
';
        $buffer .= $indent . '  <section class="middle tab-bar-section">
';
        $buffer .= $indent . '    <h1 class="title">Gregory</h1>
';
        $buffer .= $indent . '  </section>
';
        $buffer .= $indent . '</nav>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Navigation en desktop (top-bar) : visible en grand écran seulement -->
';
        $buffer .= $indent . '<nav class="top-bar show-for-large-up" data-topbar>
';
        $buffer .= $indent . '  <ul class="title-area">
';
        $buffer .= $indent . '    <li class="name">
';
        $buffer .= $indent . '      <h1><a href="#">Gregory</a></h1>
';
        $buffer .= $indent . '    </li>
';
        $buffer .= $indent . '    <!-- Icône hamburger inutile en desktop, donc on ne le met pas ici -->
';
        $buffer .= $indent . '  </ul>
';
        $buffer .= $indent . '  <section class="top-bar-section">
';
        $buffer .= $indent . '    <ul class="right">
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
        $buffer .= $indent . '      <!-- Ajoutez d’autres liens si nécessaire -->
';
        $buffer .= $indent . '    </ul>
';
        $buffer .= $indent . '  </section>
';
        $buffer .= $indent . '</nav>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Menu mobile (off-canvas) -->
';
        $buffer .= $indent . '<aside class="left-off-canvas-menu">
';
        $buffer .= $indent . '  <ul class="off-canvas-list">
';
        $buffer .= $indent . '    <li><label>Gregory</label></li>
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
        $buffer .= $indent . '</aside>
';

        return $buffer;
    }
}
