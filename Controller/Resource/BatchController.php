<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;

class BatchController extends ResourceController
{
    use ApiTrait;

    public function batchAction()
    {
        $config = $this->getActionConfig();

        return $this
            ->batchCommand($config['command'])
            ->commandParameters(isset($config['command_parameters']) ? $config['command_parameters'] : [])
            ->redirect($config['redirect'])
            ->getResponse();
    }
}
