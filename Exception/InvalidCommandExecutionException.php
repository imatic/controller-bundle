<?php
namespace Imatic\Bundle\ControllerBundle\Exception;

class InvalidCommandExecutionException extends \LogicException
{
    public function __construct($name)
    {
        $message = \sprintf('Execution of invalid command "%s"', $name);
        parent::__construct($message);
    }
}
