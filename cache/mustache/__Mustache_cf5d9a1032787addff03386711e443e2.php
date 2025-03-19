<?php

class __Mustache_cf5d9a1032787addff03386711e443e2 extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';
        $newContext = array();

        // 'message' section
        $value = $context->find('message');
        $buffer .= $this->sectionBb2b5f890e6d92bd9a3928908934c211($context, $indent, $value);

        return $buffer;
    }

    private function sectionF3b568a540c0538e24aa53881d9e6d1f(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' red ';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= ' red ';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBb2b5f890e6d92bd9a3928908934c211(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="medium-12 columns message padding5" style = "border: solid 2px {{#erreur}} red {{/erreur}} {{^erreur}} #6AC17C {{/erreur}};">
		<span style="color: {{#erreur}} red {{/erreur}} {{^erreur}} #6AC17C {{/erreur}};">
			{{{message}}}
		</span>
	</div>
';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '	<div class="medium-12 columns message padding5" style = "border: solid 2px ';
                // 'erreur' section
                $value = $context->find('erreur');
                $buffer .= $this->sectionF3b568a540c0538e24aa53881d9e6d1f($context, $indent, $value);
                $buffer .= ' ';
                // 'erreur' inverted section
                $value = $context->find('erreur');
                if (empty($value)) {
                    
                    $buffer .= ' #6AC17C ';
                }
                $buffer .= ';">
';
                $buffer .= $indent . '		<span style="color: ';
                // 'erreur' section
                $value = $context->find('erreur');
                $buffer .= $this->sectionF3b568a540c0538e24aa53881d9e6d1f($context, $indent, $value);
                $buffer .= ' ';
                // 'erreur' inverted section
                $value = $context->find('erreur');
                if (empty($value)) {
                    
                    $buffer .= ' #6AC17C ';
                }
                $buffer .= ';">
';
                $buffer .= $indent . '			';
                $value = $this->resolveValue($context->find('message'), $context, $indent);
                $buffer .= $value;
                $buffer .= '
';
                $buffer .= $indent . '		</span>
';
                $buffer .= $indent . '	</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}
