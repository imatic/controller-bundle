<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApi;

class ShowController extends ResourceController
{
    /**
     * @return ShowApi
     */
    protected function show()
    {
        return $this->getApi('imatic_controller.api.show', 'show', func_get_args());
    }

    public function showAction($id)
    {
        $config = $this->getConfig();

        return $this
            ->show(new $config['query']($id, $config['entity']))
            ->setTemplateName($config['template'])
            ->addTemplateVariable('config', $config)
            ->getResponse();
    }
}
