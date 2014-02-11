<?php
namespace Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Controller;

use Imatic\Bundle\ControllerBundle\Controller\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Listing\ListingApiTrait;
use Imatic\Bundle\ControllerBundle\Controller\Api\Show\ShowApiTrait;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserListQuery;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\TestProject\ImaticControllerBundle\Data\UserQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @Config\Route("/api")
 */
class TestController implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ApiTrait;
    use ShowApiTrait;
    use ListingApiTrait;

    /**
     * @Config\Route("/show/{id}")
     */
    public function showAction($id)
    {
        return $this
            ->show(new UserQuery($id))
            ->setTemplateName('AppImaticControllerBundle:Test:show.html.twig')
            ->getResponse();
    }

    /**
     * @Config\Route("/list")
     */
    public function listAction()
    {
        return $this
            ->listing(new UserListQuery(3))
            ->setTemplateName('AppImaticControllerBundle:Test:list.html.twig')
            ->getResponse();
    }
}
