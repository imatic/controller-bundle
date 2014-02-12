<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Mock;

use Imatic\Bundle\ControllerBundle\Controller\Feature\Template\TemplateFeature;

class TemplateMock extends TemplateFeature
{
    public function __construct()
    {
    }

    public function render()
    {
        $combineKeyValue = function ($name, $value) {
            return sprintf('%s: %s', $name, $value);
        };
        $contactElements = function (&$result, $element) {
            return ($result ? $result . ', ' : $result) . $element;
        };

        $templateVars = array('template' => $this->getTemplateName());
        $templateVars += $this->getTemplateVariables();
        $templateVals = array_keys($templateVars);

        return array_reduce(array_map($combineKeyValue, $templateVals, $templateVars), $contactElements, '');
    }
}
