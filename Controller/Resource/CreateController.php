<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;

class CreateController extends ResourceController
{
    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function createAction()
    {
        $config = $this->getActionConfig();

        return $this
            ->form($config['form'], null, array_merge(['data_class' => $config['entity']], $config['form_options']))
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
