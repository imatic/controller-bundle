<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

trait CommandApiTrait
{
    /**
     * @return CommandApi
     */
    public function command()
    {
        return $this->getApi('command', func_get_args());
    }
}
