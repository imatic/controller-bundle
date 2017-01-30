<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Resource\ResourceConfigurationRepository;

class ResourceConfigurationRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetActions()
    {
        $config = [
            'book' => [
                'list' => ['book-list'],
                'edit' => ['book-edit'],
            ],
            'author' => [
                'list' => ['author-list'],
                'edit' => ['author-edit'],
            ],
        ];

        $expected = [
            ['book-list'],
            ['book-edit'],
            ['author-list'],
            ['author-edit'],
        ];

        $repository = new ResourceConfigurationRepository($config);
        $all = $repository->getActions();

        $this->assertEquals($expected, $all);
    }

    public function testGetAction()
    {
        $config = [
            'book' => [
                'list' => ['book-list'],
                'edit' => ['book-edit'],
            ],
        ];

        $repository = new ResourceConfigurationRepository($config);
        $all = $repository->getAction('book', 'edit');

        $this->assertEquals(['book-edit'], $all);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Resource:action "book:edit" not found
     */
    public function testInvalidGetActionThrows()
    {
        $config = [];

        $repository = new ResourceConfigurationRepository($config);
        $repository->getAction('book', 'edit');
    }

    public function testGetResource()
    {
        $config = [
            'book' => [
                'list' => ['book-list'],
                'edit' => ['book-edit'],
            ],
        ];

        $repository = new ResourceConfigurationRepository($config);
        $all = $repository->getResource('book');

        $this->assertEquals($config['book'], $all);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Resource "book" not found
     */
    public function testInvalidGetResourceThrows()
    {
        $config = [];

        $repository = new ResourceConfigurationRepository($config);
        $repository->getResource('book');
    }
}
