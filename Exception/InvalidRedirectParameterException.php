<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Exception;

class InvalidRedirectParameterException extends \InvalidArgumentException
{
    public function __construct($parameters)
    {
        if (\is_object($parameters)) {
            $type = \get_class($parameters);
        } else {
            $type = \gettype($parameters);
        }
        $message = \sprintf('Redirect parameters must be an instance of Closure or array, "%s" given', $type);
        parent::__construct($message);
    }
}
