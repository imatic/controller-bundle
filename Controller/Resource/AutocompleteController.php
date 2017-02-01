<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Api\Ajax\AutocompleteApi;

class AutocompleteController extends ResourceController
{
    /**
     * @return AutocompleteApi
     */
    public function autocomplete()
    {
        return $this->getApi('imatic_controller.api.autocomplete', 'autocomplete', func_get_args());
    }

    public function autocompleteAction()
    {
        $config = $this->getActionConfig();

        return $this
            ->autocomplete(new $config['query']($config['entity']))
            ->getResponse();
    }
}
