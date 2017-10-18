<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Template;

trait TemplateFeatureTrait
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

    public function addTemplateVariables(array $templateVariables)
    {
        $this->template->addTemplateVariables($templateVariables);

        return $this;
    }
}
