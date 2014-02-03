<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\Entity;

class User
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return 'User ' . $this->id;
    }

    public function __toString()
    {
        return $this->getName();
    }
}