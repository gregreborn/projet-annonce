<?php

class __Mustache_b76d1b525ba1f60fae7c303808eb7755 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<div class="site-footer text-center">
';
        $buffer .= $indent . '    <p>&copy; ';
        $value = $this->resolveValue($context->find('currentYear'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= ' Mon Site. Tous droits réservés.</p>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '  ';

        return $buffer;
    }
}
