<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Feature\Redirect;

trait RedirectFeatureTrait
{
    public function successRedirect($routeName, $parameters = null)
    {
        $this->redirect->setSuccessRedirect($routeName, $parameters);

        return $this;
    }

    public function errorRedirect($routeName, $parameters = null)
    {
        $this->redirect->setErrorRedirect($routeName, $parameters);

        return $this;
    }

    public function redirect($routeName, $parameters = null)
    {
        $this->redirect->setErrorRedirect($routeName, $parameters);
        $this->redirect->setSuccessRedirect($routeName, $parameters);

        return $this;
    }
}
