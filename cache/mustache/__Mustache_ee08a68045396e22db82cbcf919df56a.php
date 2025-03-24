<?php

class __Mustache_ee08a68045396e22db82cbcf919df56a extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!-- Mobile header with hamburger (visible on small screens only) -->
';
        $buffer .= $indent . '<nav class="tab-bar show-for-small-only">
';
        $buffer .= $indent . '  <section class="left-small">
';
        $buffer .= $indent . '    <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
';
        $buffer .= $indent . '  </section>
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
        $buffer .= $indent . '<!-- Desktop header with logo only (visible on medium-up) -->
';
        $buffer .= $indent . '<header class="site-header show-for-medium-up" style="background: #f04124; color: #fff; padding: 1rem;">
';
        $buffer .= $indent . '  <div class="logo">
';
        $buffer .= $indent . '    <h1 style="margin: 0;">Gregory</h1>
';
        $buffer .= $indent . '  </div>
';
        $buffer .= $indent . '</header>
';

        return $buffer;
    }
}
