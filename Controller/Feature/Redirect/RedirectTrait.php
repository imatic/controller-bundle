<?php

namespace Imatic\Bundle\ControllerBundle\Api\Feature;

trait RedirectTrait
{
    public function successRedirect($routeName, array $parameters = [])
    {
        return $this;
    }

    protected function getSuccessRedirectUrl()
    {
    }
}
