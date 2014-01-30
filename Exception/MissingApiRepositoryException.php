<?php

namespace Imatic\Bundle\ControllerBundle\Exception;

class MissingApiRepositoryException extends \LogicException
{

    public function __construct()
    {
        parent::__construct('The ApiRepository is not initialized, please call addApi or setContainer method first');
    }
}