<?php

class __Mustache_01461d4b8f22da9d5dd0ede864be2932 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Mobile header with hamburger (visible on small screens only) -->
';
        $buffer .= $indent . '<nav class="tab-bar show-for-medium-down modern-header">
';
        $buffer .= $indent . '  <section class="left-small">
';
        $buffer .= $indent . '    <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
';
        $buffer .= $indent . '  </section>
';
        $buffer .= $indent . '  <section class="middle tab-bar-section">
';
        $buffer .= $indent . '    <h1 class="title">MarketPlace</h1>
';
        $buffer .= $indent . '  </section>
';
        $buffer .= $indent . '</nav>
';
        $buffer .= $indent . '
';
        $buffer .= $indent . '<!-- Desktop header with logo only (visible on medium-up) -->
';
        $buffer .= $indent . '<header class="site-header show-for-medium-up">
';
        $buffer .= $indent . '  <div class="logo">
';
        $buffer .= $indent . '    <h1>MarketPlace</h1>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '</header>
';

        return $buffer;
    }
}
