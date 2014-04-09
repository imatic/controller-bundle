<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Api\Command;

trait BatchCommandApiTrait
{
    /**
     * @return BatchCommandApi
     */
    public function batchCommand()
    {
        return $this->getApi('batchCommand', func_get_args());
    }
}
