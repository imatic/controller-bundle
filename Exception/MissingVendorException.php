<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Exception;

class MissingVendorException extends ApiNotFoundException
{
    public function __construct($name, $vendor)
    {
        $this->message = \sprintf(
            'Api "%s" not found. Make sure you have "%s" installed and its bundle is registered in kernel.',
            $name,
            $vendor
        );
    }
}
