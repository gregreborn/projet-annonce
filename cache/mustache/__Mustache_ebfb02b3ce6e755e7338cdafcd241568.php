<?php

class __Mustache_ebfb02b3ce6e755e7338cdafcd241568 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<!DOCTYPE html>
';
        $buffer .= $indent . '<html lang="fr">
';
        $buffer .= $indent . '<head>
';
        $buffer .= $indent . '    <meta charset="UTF-8">
';
        $buffer .= $indent . '    <meta name="viewport" content="width=device-width, initial-scale=1.0">
';
        $buffer .= $indent . '    <title>';
        $value = $this->resolveValue($context->find('title'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '</title>
';
        $buffer .= $indent . '    <link rel="stylesheet" href="C:\\wamp64\\www\\projet-annonce\\public\\assets\\css\\base.css">
';
        $buffer .= $indent . '    <link rel="stylesheet" href="C:\\wamp64\\www\\projet-annonce\\public\\assets\\libs\\foundation\\css\\foundation.min.css">
';
        $buffer .= $indent . '</head>
';
        $buffer .= $indent . '<body>
';
        $buffer .= $indent . '    <header>
';
        if ($partial = $this->mustache->loadPartial('entete')) {
            $buffer .= $partial->renderInternal($context, $indent . '        ');
        }
        $buffer .= $indent . '    </header>
';
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    <main class="grid-container">
';
        $buffer .= $indent . '        ';
        $value = $this->resolveValue($context->find('content'), $context, $indent);
        $buffer .= $value;
        $buffer .= '
';
        $buffer .= $indent . '    </main>
';
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    <footer>
';
        if ($partial = $this->mustache->loadPartial('footer')) {
            $buffer .= $partial->renderInternal($context, $indent . '        ');
        }
        $buffer .= $indent . '    </footer>
';
        $buffer .= $indent . '    
';
        if ($partial = $this->mustache->loadPartial('message')) {
            $buffer .= $partial->renderInternal($context, $indent . '    ');
        }
        $buffer .= $indent . '    
';
        $buffer .= $indent . '    <script src="C:\\wamp64\\www\\projet-annonce\\public\\assets\\libs\\jquery.min.js"></script>
';
        $buffer .= $indent . '    <script src="C:\\wamp64\\www\\projet-annonce\\public\\assets\\libs\\foundation\\js\\foundation.min.js"></script>
';
        $buffer .= $indent . '    <script src="C:\\wamp64\\www\\projet-annonce\\public\\assets\\libs\\jquery-ui-1.11.4.custom\\jquery-ui.js"></script>
';
        $buffer .= $indent . '    <script src="C:\\wamp64\\www\\projet-annonce\\public\\assets\\libs\\jquery.scrollTo.js"></script>
';
        $buffer .= $indent . '    <script src="C:\\wamp64\\www\\projet-annonce\\public\\assets\\js\\base.js"></script>
';
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>
';

        return $buffer;
    }
}
