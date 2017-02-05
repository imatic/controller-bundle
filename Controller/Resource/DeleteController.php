<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\Command\CommandApi;

class DeleteController extends ResourceController
{
    /**
     * @return CommandApi
     */
    public function command()
    {
        return $this->getApi('imatic_controller.api.command', 'command', func_get_args());
    }

    public function deleteAction($id)
    {
        $config = $this->getActionConfig();
        $commandParameters = [
            'item' => $id,
            'class' => $config['entity'],
            'query_object' => new $config['query']($id, $config['entity']),
        ];

        return $this
            ->command($config['command'], $commandParameters)
            ->redirect($config['redirect'])
            ->getResponse();
    }
}
