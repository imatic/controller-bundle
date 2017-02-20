<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

class ShowController extends ResourceController
{
    public function showAction($id)
    {
        $config = $this->getActionConfig();

        return $this
            ->show(new $config['query']($id, $config['entity']))
            ->addDataAuthorizationCheck(strtoupper($config['name']), 'item')
            ->enableDataAuthorization($config['data_authorization'])
            ->setTemplateName($config['template'])
            ->addTemplateVariable('action', $config)
            ->addTemplateVariable('resource', $this->getResourceConfig())
            ->getResponse();
    }
}
