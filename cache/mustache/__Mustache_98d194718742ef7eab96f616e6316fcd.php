<?php

class __Mustache_98d194718742ef7eab96f616e6316fcd extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/accueil">Accueil</a></li>
';
        $buffer .= $indent . '<li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/annonces">Annonces</a></li>
';
        $buffer .= $indent . '<li><a href="';
        $value = $this->resolveValue($context->find('SERVER_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/choix_annonce">Publier une annonce</a></li>
';

        return $buffer;
    }
}
