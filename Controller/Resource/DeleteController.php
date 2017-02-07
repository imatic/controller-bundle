<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\Command\CommandApi;
use Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApi;
use Symfony\Component\HttpFoundation\Request;

class DeleteController extends ResourceController
{
    /**
     * @return CommandApi
     */
    public function command()
    {
        return $this->getApi('imatic_controller.api.command', 'command', func_get_args());
    }

    /**
     * @return ShowApi
     */
    public function show()
    {
        return $this->getApi('imatic_controller.api.show', 'show', func_get_args());
    }

    public function deleteAction(Request $request, $id)
    {
        $config = $this->getActionConfig();

        if ($request->isMethod('delete')) {
            $commandParameters = [
                'item' => $id,
                'class' => $config['entity'],
                'query_object' => new $config['query']($id, $config['entity']),
            ];

            return $this
                ->command($config['command'], $commandParameters)
                ->redirect($config['redirect'])
                ->getResponse();
        } else {
            return $this->show(new $config['query']($id, $config['entity']))
                ->setTemplateName($config['template'])
                ->addTemplateVariable('action', $config)
                ->addTemplateVariable('resource', $this->getResourceConfig())
                ->getResponse();
        }
    }
}
