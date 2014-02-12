<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect;

trait RedirectTrait
{
    public function successRedirect($routeName, $parameters)
    {
        $this->redirect->setSuccessRedirect($routeName, $parameters);

        return $this;
    }
}
