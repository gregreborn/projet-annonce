<?php

class __Mustache_86e3506bcb061ee0a22860352c148141 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<div class="site-footer">
';
        $buffer .= $indent . '    <p>&copy; ';
        $value = $this->resolveValue($context->find('currentYear'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= ' Mon Site. Tous droits réservés.</p>
';
        $buffer .= $indent . '</div>
';

        return $buffer;
    }
}
