<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Template;

use Twig\Environment;

class TemplateFeature
{
    /**
     * @var Environment
     */
    private $twig;

    private $templateName;

    private $templateVariables;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
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
        return $this->twig->render($this->templateName, $this->templateVariables);
    }
}
