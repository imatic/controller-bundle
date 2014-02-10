<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

use Symfony\Component\Templating\EngineInterface;

class Template
{
    /**
     * @var EngineInterface
     */
    private $templating;

    private $templateName;

    private $templateVariables;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
        $this->templateVariables = [];
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function setTemplateName($template)
    {
        $this->templateName = $template;
    }

    public function addTemplateVariable($name, $value)
    {
        $this->templateVariables[$name] = $value;
    }

    public function getTemplateVariables()
    {
        return $this->templateVariables;
    }

    public function addTemplateVariables(array $templateVariables)
    {
        foreach ($templateVariables as $name => $variable) {
            $this->addTemplateVariable($name, $variable);
        }
    }

    public function render()
    {
        return $this->templating->render($this->templateName, $this->templateVariables);
    }
}
