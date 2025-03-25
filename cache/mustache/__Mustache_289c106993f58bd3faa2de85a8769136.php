<?php

class __Mustache_289c106993f58bd3faa2de85a8769136 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<footer class="row text-center">
';
        $buffer .= $indent . '    <div class="small-12 columns">
';
        $buffer .= $indent . '      <p style="color: var(--text-muted); padding: 2rem 0; font-size: 0.9rem;">
';
        $buffer .= $indent . '        &copy; ';
        $value = $this->resolveValue($context->find('currentYear'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= ' Mon Site. Tous droits réservés.
';
        $buffer .= $indent . '      </p>
';
        $buffer .= $indent . '    </div>
';
        $buffer .= $indent . '  </footer>
';
        $buffer .= $indent . '  ';

        return $buffer;
    }
}
