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

    public function testGet()
    {
        $config = [
            'book' => [
                'list' => ['book-list'],
                'edit' => ['book-edit'],
            ],
        ];

        $repository = new ResourceConfigurationRepository($config);
        $all = $repository->get('book', 'edit');

        $this->assertEquals(['book-edit'], $all);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Resource:action "book:edit" not found
     */
    public function testInvalidGetThrows()
    {
        $config = [];

        $repository = new ResourceConfigurationRepository($config);
        $repository->get('book', 'edit');
    }
}
