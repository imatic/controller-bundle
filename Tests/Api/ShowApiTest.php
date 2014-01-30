<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Api\ShowApi;

use Imatic\Bundle\ControllerBundle\Api\ShowApi;
use Imatic\Bundle\ControllerBundle\Tests\Fixtures\Data\UserQuery;
use Imatic\Bundle\ControllerBundle\Tests\Mock\TemplateMock;
use Imatic\Bundle\ControllerBundle\Tests\Mock\UserDataMock;

class ShowApiTest extends \PHPUnit_Framework_TestCase
{
    public function testShow()
    {
        $showApi = new ShowApi(new UserDataMock(), new TemplateMock());
        $content = $showApi
            ->show(new UserQuery(1))
            ->setTemplateName('test.twig')
            ->getResponse()
            ->getContent();

        $this->assertEquals('template: test.twig, item: User 1', $content);
    }
}
