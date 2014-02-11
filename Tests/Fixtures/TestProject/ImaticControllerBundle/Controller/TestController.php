<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Controller;

use Imatic\Bundle\ControllerBundle\Controller\Api\Listing\ListingApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class TestController
{
    use ApiTrait;
    use ContainerAwareTrait;
    use ShowApiTrait;
    use ListingApiTrait;

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
