<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

class BatchController extends ResourceController
{
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
