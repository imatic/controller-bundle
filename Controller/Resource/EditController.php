<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

class EditController extends ResourceController
{
    public function editAction($id)
    {
        $config = $this->getActionConfig();

        return $this
            ->form($config['form'], null, ['data_class' => $config['entity']])
            ->commandName($config['command'])
            ->commandParameters(['class' => $config['entity']])
            ->edit(new $config['query']($id, $config['entity']))
            ->successRedirect($config['redirect'], ['id' => $id])
            ->setTemplateName($config['template'])
            ->addTemplateVariable('action', $config)
            ->addTemplateVariable('resource', $this->getResourceConfig())
            ->addDataAuthorizationCheck(strtoupper($config['name']), 'item')
            ->enableDataAuthorization($config['data_authorization'])
            ->getResponse();
    }
}
