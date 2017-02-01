<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\Form\FormApi;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;

class CreateController extends ResourceController
{
    /**
     * @return FormApi
     */
    public function form()
    {
        return $this->getApi('imatic_controller.api.form', 'form', func_get_args());
    }

    public function createAction()
    {
        $config = $this->getActionConfig();

        return $this
            ->form($config['form'], null, ['data_class' => $config['entity']])
            ->commandName($config['command'])
            ->successRedirect($config['redirect'], function (CommandResultInterface $result, $item) {
                return ['id' => $item->getId()];
            })
            ->setTemplateName($config['template'])
            ->addTemplateVariable('resource', $this->getResourceConfig())
            ->getResponse();
    }
}
