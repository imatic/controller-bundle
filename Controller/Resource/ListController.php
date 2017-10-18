<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

class ListController extends ResourceController
{
    public function listAction()
    {
        $action = $this->getActionConfig();

        return $this
            ->listing(new $action['query']($action['entity']))
//            ->filter('imatic_directory_company_filter')
            ->setTemplateName($action['template'])
            ->addTemplateVariable('action', $action)
            ->addTemplateVariable('resource', $this->getResourceConfig())
            ->getResponse();
    }
}
