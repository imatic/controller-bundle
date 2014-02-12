<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Form;

trait FormApiTrait
{
    /**
     * @return FormApi
     */
    public function form()
    {
        return $this->getApi('form', func_get_args());
    }
}
