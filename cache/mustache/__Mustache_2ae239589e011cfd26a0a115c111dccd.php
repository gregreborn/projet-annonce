<?php

class __Mustache_2ae239589e011cfd26a0a115c111dccd extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/jquery.min.js"></script>
';
        $buffer .= $indent . '<script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/foundation/js/foundation.min.js"></script>
';
        $buffer .= $indent . '<script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
';
        $buffer .= $indent . '<script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/jquery.scrollTo.js"></script>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<script src="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/js/base.js"></script>
';
        $buffer .= $indent . '
';

        return $buffer;
    }
}
