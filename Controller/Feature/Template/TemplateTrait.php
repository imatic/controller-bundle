<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Template;

trait TemplateTrait
{
    public function setTemplateName($template)
    {
        $this->template->setTemplateName($template);

        return $this;
    }

    public function addTemplateVariable($name, $value)
    {
        $this->template->addTemplateVariable($name, $value);

        return $this;
    }
}
