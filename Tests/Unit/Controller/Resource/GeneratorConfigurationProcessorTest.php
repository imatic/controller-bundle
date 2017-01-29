<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Controller\Resource;

use Imatic\Bundle\ControllerBundle\Controller\Resource\ResourceConfigurationProcessor;

class ResourceConfigurationProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testFillActionTypes()
    {
        $actionConfig = [
            'list' => [],
            'edit' => ['type' => 'batch'],
            'show' => [],
            'show_details' => ['type' => 'show'],
        ];

        $expected = [
            'list' => ['type' => 'list'],
            'edit' => ['type' => 'batch'],
            'show' => ['type' => 'show'],
            'show_details' => ['type' => 'show'],
        ];

        $processor = new ResourceConfigurationProcessor();
        $filled = $processor->fillActionTypes($actionConfig);

        $this->assertEquals($expected, $filled);
    }

    public function testFillActionNames()
    {
        $actionConfig = [
            'list' => [],
            'edit' => [],
            'show' => [],
            'show_details' => [],
        ];

        $expected = [
            'list' => ['action' => 'list'],
            'edit' => ['action' => 'edit'],
            'show' => ['action' => 'show'],
            'show_details' => ['action' => 'show_details'],
        ];

        $processor = new ResourceConfigurationProcessor();
        $filled = $processor->fillActionNames($actionConfig);

        $this->assertEquals($expected, $filled);
    }

    public function testFillResourceNames()
    {
        $config = [
            'book' => [
                'actions' => [
                    'list' => null,
                    'edit' => null,
                ],
            ],
        ];

        $expected = [
            'book' => [
                'resource' => 'book',
                'actions' => [
                    'list' => ['resource' => 'book'],
                    'edit' => ['resource' => 'book'],
                ],
            ],
        ];

        $processor = new ResourceConfigurationProcessor();
        $filled = $processor->fillResourceNames($config);

        $this->assertEquals($expected, $filled);
    }

    public function testPreProcessResources()
    {
        $config = [
            'book' => [
                'actions' => [
                    'list' => null,
                    'edit' => null,
                    'show_details' => ['type' => 'show'],
                ],
            ],
        ];

        $expected = [
            'book' => [
                'config' => ['route' => ['path' => '/book']],
                'resource' => 'book',
                'actions' => [
                    'list' => ['resource' => 'book', 'action' => 'list', 'type' => 'list'],
                    'edit' => ['resource' => 'book', 'action' => 'edit', 'type' => 'edit'],
                    'show_details' => ['resource' => 'book', 'action' => 'show_details', 'type' => 'show'],
                ],
            ],
        ];

        $processor = new ResourceConfigurationProcessor();
        $filled = $processor->preProcessResources($config);

        $this->assertEquals($expected, $filled);
    }

    public function testMergeActionConfiguration()
    {
        $defaultConfig = [
            'list' => [
                'template' => 'list_template.html.twig',
                'route' => ['path' => '/', 'method' => ['get']],
            ],
            'edit' => [
                'template' => 'edit_template.html.twig',
                'route' => ['path' => '/:id/edit', 'method' => ['get', 'post']],
            ],
        ];

        $resourcesConfig = [
            'book' => [
                'actions' => [
                    'list' => null,
                    'edit' => [
                        'query' => 'ItemQuery',
                    ],
                ],
            ],
        ];

        $expected = [
            'book' => [
                'config' => ['route' => ['path' => '/book']],
                'resource' => 'book',
                'actions' => [
                    'list' => [
                        'template' => 'list_template.html.twig',
                        'route' => ['path' => '/', 'method' => ['get']],
                        'resource' => 'book',
                        'action' => 'list',
                        'type' => 'list',
                    ],
                    'edit' => [
                        'template' => 'edit_template.html.twig',
                        'route' => ['path' => '/:id/edit', 'method' => ['get', 'post']],
                        'query' => 'ItemQuery',
                        'resource' => 'book',
                        'action' => 'edit',
                        'type' => 'edit',
                    ],
                ],
            ],
        ];

        $processor = new ResourceConfigurationProcessor();
        $merged = $processor->mergeActionConfiguration($defaultConfig, $processor->preProcessResources($resourcesConfig));

        $this->assertEquals($expected, $merged);
    }

    public function testProcess()
    {
        $defaultConfig = [
            'defaults' => [
                'list' => [
                    'template' => 'list_template.html.twig',
                    'route' => ['path' => '/', 'method' => ['get']],
                    'collection' => true,
                ],
                'edit' => [
                    'template' => 'edit_template.html.twig',
                    'route' => ['path' => '/:id/edit', 'method' => ['get', 'post']],
                    'collection' => false,
                ],
            ],
        ];

        $resourcesConfig = [
            'book' => [
                'config' => [
                    'query' => [
                        'collection' => 'ListQuery',
                    ],
                ],
                'actions' => [
                    'list' => null,
                    'edit' => [
                        'query' => 'ItemQuery',
                    ],
                ],
            ],
        ];

        $expected = [
            'book' => [
                'list' => [
                    'template' => 'list_template.html.twig',
                    'route' => ['path' => '/book', 'method' => ['get']],
                    'query' => 'ListQuery',
                    'resource' => 'book',
                    'action' => 'list',
                    'type' => 'list',
                    'collection' => true,
                ],
                'edit' => [
                    'template' => 'edit_template.html.twig',
                    'route' => ['path' => '/book/:id/edit', 'method' => ['get', 'post']],
                    'query' => 'ItemQuery',
                    'resource' => 'book',
                    'action' => 'edit',
                    'type' => 'edit',
                    'collection' => false,
                ],
            ],
        ];

        $this->validateConfigurationGuard($defaultConfig, $resourcesConfig);

        $processor = new ResourceConfigurationProcessor();
        $merged = $processor->process($defaultConfig, $resourcesConfig);

        $this->assertEquals($expected, $merged);
    }

    private function validateConfigurationGuard(array $resourcesConfig, array $resources)
    {
        $resourceConfigurationProcessor = new ResourceConfigurationProcessor();
        $config = $resourceConfigurationProcessor->process($resourcesConfig, $resources);

        $this->assertInternalType('array', $config);
    }
}
