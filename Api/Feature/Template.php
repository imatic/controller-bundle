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

    private $templateVariableName;

    private $templateVariables;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function setTemplateName($template)
    {
        $this->templateName = $template;
    }

    public function getTemplateVariableName()
    {
        return $this->templateVariableName;
    }

    public function setTemplateVariableName($name)
    {
        $this->templateVariableName = $name;
    }

    public function addTemplateVariable($name, $value)
    {
        $this->templateVariables[$name] = $value;
    }

    public function getTemplateVariables()
    {
        return $this->templateVariables;
    }

    public function render()
    {
        return $this->templating->render($this->templateName, $this->templateVariables);
    }
}