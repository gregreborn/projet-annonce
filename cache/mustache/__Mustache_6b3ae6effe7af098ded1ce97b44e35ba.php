<?php

class __Mustache_6b3ae6effe7af098ded1ce97b44e35ba extends Mustache_Template
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
        $buffer .= $indent . '    <title>Publier une Annonce</title>
';
        $buffer .= $indent . '    <link rel="stylesheet" href="/assets/css/base.css">
';
        $buffer .= $indent . '    <link rel="stylesheet" href="/assets/libs/foundation/css/foundation.min.css">
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
        $buffer .= $indent . '        <h1 class="text-center">Publier une Annonce</h1>
';
        $buffer .= $indent . '        <p class="text-center">Choisissez le type d\'annonce que vous souhaitez publier :</p>
';
        $buffer .= $indent . '        
';
        $buffer .= $indent . '        <div class="grid-x grid-margin-x align-center">
';
        $buffer .= $indent . '            <div class="cell small-12 medium-6">
';
        $buffer .= $indent . '                <a href="soumission_offre.html" class="button expanded">J\'OFFRE</a>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '            <div class="cell small-12 medium-6">
';
        $buffer .= $indent . '                <a href="soumission_besoin.html" class="button hollow expanded">J\'AI BESOIN</a>
';
        $buffer .= $indent . '            </div>
';
        $buffer .= $indent . '        </div>
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
        $buffer .= $indent . '</body>
';
        $buffer .= $indent . '</html>
';

        return $buffer;
    }
}
