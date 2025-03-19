<?php

class __Mustache_22d2be4a105957f46cd0b4b1323fa4ec extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!DOCTYPE html>
';
        $buffer .= $indent . '<html class="no-js" lang="fr">
';
        $buffer .= $indent . '    <head>
';
        $buffer .= $indent . '	    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
';
        $buffer .= $indent . '        <meta charset="utf-8">
';
        $buffer .= $indent . '        <meta name="viewport" content="width=device-width, initial-scale=1.0">
';
        $buffer .= $indent . '        <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/font-awesome/foundation-icons.css">
';
        $buffer .= $indent . '        <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/foundation/css/normalize.css">
';
        $buffer .= $indent . '        <link rel="stylesheet" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/foundation/css/foundation.min.css">
';
        $buffer .= $indent . '        <link href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/libs/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
';
        $buffer .= $indent . '       
';
        $buffer .= $indent . '        <link rel="stylesheet" type="text/css" href="';
        $value = $this->resolveValue($context->find('PUBLIC_ABSOLUTE_PATH'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '/css/base.css">        
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '        ';

        return $buffer;
    }
}
