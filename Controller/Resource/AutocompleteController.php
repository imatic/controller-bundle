<?php
namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

class AutocompleteController extends ResourceController
{
    public function autocompleteAction()
    {
        $config = $this->getActionConfig();

        return $this
            ->autocomplete(new $config['query']($config['entity']))
            ->getResponse();
    }
}
