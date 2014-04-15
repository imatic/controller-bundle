<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Ajax;

trait AutocompleteApiTrait
{
    /**
     * @return AutocompleteApi
     */
    public function autocomplete()
    {
        return $this->getApi('autocomplete', func_get_args());
    }
}
