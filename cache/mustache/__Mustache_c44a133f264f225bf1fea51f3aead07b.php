<?php

class __Mustache_c44a133f264f225bf1fea51f3aead07b extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        if ($partial = $this->mustache->loadPartial('entete')) {
            $buffer .= $partial->renderInternal($context);
        }

        return $buffer;
    }
}
