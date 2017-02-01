<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\Form\FormApi;

class EditController extends ResourceController
{
    /**
     * @return FormApi
     */
    public function form()
    {
        return $this->getApi('imatic_controller.api.form', 'form', func_get_args());
    }

    public function editAction($id)
    {
        $config = $this->getActionConfig();

        return $this
            ->form($config['form'], null, ['data_class' => $config['entity']])
            ->commandName($config['command'])
            ->edit(new $config['query']($id, $config['entity']))
            ->successRedirect($config['redirect'], ['id' => $id])
            ->setTemplateName($config['template'])
            ->addTemplateVariable('resource', $this->getResourceConfig())
            ->getResponse();
    }
}
