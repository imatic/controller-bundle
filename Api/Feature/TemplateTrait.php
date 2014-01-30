<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

trait TemplateTrait
{
    public function setTemplateName($template)
    {
        $this->template->setTemplateName($template);

        return $this;
    }

    public function setTemplateVariableName($name)
    {
        $this->template->setTemplateVariableName($name);

        return $this;
    }

    public function addTemplateVariable($name, $value)
    {
        $this->template->addTemplateVariable($name, $value);

        return $this;
    }
}