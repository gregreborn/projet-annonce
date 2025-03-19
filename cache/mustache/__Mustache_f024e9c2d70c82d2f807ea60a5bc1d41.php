<?php

class __Mustache_f024e9c2d70c82d2f807ea60a5bc1d41 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<div class="site-header">
';
        $buffer .= $indent . '    <div class="logo">
';
        $buffer .= $indent . '        <h1>Mon Site</h1>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '    <nav class="main-nav">
';
        $buffer .= $indent . '        <ul>
';
        $buffer .= $indent . '            <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/accueil">Accueil</a></li>
';
        $buffer .= $indent . '            <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/annonces">Annonces</a></li>
';
        $buffer .= $indent . '            <li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/choix_annonce">Publier une annonce</a></li>
';
        $buffer .= $indent . '        </ul>
';
        $buffer .= $indent . '    </nav>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }
}
