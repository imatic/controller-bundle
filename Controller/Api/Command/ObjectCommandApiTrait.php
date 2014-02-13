<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

trait ObjectCommandApiTrait
{
    /**
     * @return CommandApi
     */
    public function objectCommand()
    {
        return $this->getApi('objectCommand', func_get_args());
    }
}
