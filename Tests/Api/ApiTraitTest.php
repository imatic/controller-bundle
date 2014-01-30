<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Api\ShowApi;

use Imatic\Bundle\ControllerBundle\Api\ApiRepository;
use Imatic\Bundle\ControllerBundle\Api\ApiTrait;
use Imatic\Bundle\ControllerBundle\Api\ShowApi;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\Controller\TestController;
use Imatic\Bundle\ControllerBundle\Tests\Mock\TemplateMock;
use Imatic\Bundle\ControllerBundle\Tests\Mock\UserDataMock;
use Symfony\Component\DependencyInjection\Container;

class ApiTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testDirectApiInject()
    {
        $showApi = new ShowApi(new UserDataMock(), new TemplateMock());

        $controller = new TestController();
        $controller->addApi('show', $showApi);
        $content = $controller->showAction(2)->getContent();

        $this->assertEquals('template: test.twig, item: User 2', $content);
    }

    public function testControllerApiInject()
    {
        $showApi = new ShowApi(new UserDataMock(), new TemplateMock());

        $apiRepository = new ApiRepository();
        $apiRepository->add('show', $showApi);

        $container = new Container();
        $container->set('imatic_controller.api_repository', $apiRepository);

        $controller = new TestController();
        $controller->setContainer($container);
        $content = $controller->showAction(2)->getContent();

        $this->assertEquals('template: test.twig, item: User 2', $content);
    }

    /**
     * @expectedException \Imatic\Bundle\ControllerBundle\Exception\MissingApiRepositoryException
     */
    public function testCallWithoutContainerAndRepository()
    {
        $controller = new TestController();
        $controller->showAction(2);
    }
}