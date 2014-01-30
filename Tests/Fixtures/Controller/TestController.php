<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\Controller;

use Imatic\Bundle\ControllerBundle\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\Data\UserListQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\Data\UserQuery;

class TestController
{
    use ApiTrait;

    public function showAction($id)
    {
        return $this
            ->show(new UserQuery($id))
            ->setTemplateName('test.twig')
            ->getResponse();
    }

    public function listAction()
    {
        return $this
            ->listing(new UserListQuery(3))
            ->getResponse();
    }
}