<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Show;

trait ShowApiTrait
{
    /**
     * @return ShowApi
     */
    public function show()
    {
        return $this->getApi('show', func_get_args());
    }
}
