<?php

namespace Imatic\Bundle\ControllerBundle\Exception;

class ApiNotFoundException extends \InvalidArgumentException
{
    public function __construct($name)
    {
        $message = sprintf('Api "%s" not found', $name);
        parent::__construct($message);
    }
}
