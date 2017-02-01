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

        $this
            ->command($config['command'], ['item' => $id, 'entity' => $config['entity']])
            ->redirect($config['redirect'])
            ->getResponse();
    }
}
