<?php

namespace Imatic\Bundle\ControllerBundle\Exception;

class InvalidRedirectException extends \InvalidArgumentException
{
    public function __construct($name)
    {
        $message = sprintf('Redirect not found "%s"', $name);
        parent::__construct($message);
    }
}
