<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;

class CreateController extends ResourceController
{
    public function createAction()
    {
        $config = $this->getActionConfig();

        return $this
            ->form($config['form'], null, ['data_class' => $config['entity']])
            ->commandName($config['command'])
            ->commandParameters(['class' => $config['entity']])
            ->commandParameters($config['command_parameters'] ?? [])
            ->successRedirect($config['redirect'], function (CommandResultInterface $result, $item) {
                return ['id' => $item->getId()];
            })
            ->setTemplateName($config['template'])
            ->addTemplateVariable('action', $config)
            ->addTemplateVariable('resource', $this->getResourceConfig())
            ->getResponse();
    }
}
