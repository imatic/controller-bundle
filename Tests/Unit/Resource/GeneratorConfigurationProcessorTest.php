<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Resource;

use Imatic\Bundle\ControllerBundle\Resource\ResourceConfigurationProcessor;

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
                'config' => [
                    'route' => ['path' => '/book'],
                    'translation_domain' => 'Book',
                    'name' => 'book',
                    'role' => null,
                ],
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
                'config' => [
                    'route' => ['path' => '/book'],
                    'translation_domain' => 'Book',
                    'name' => 'book',
                    'role' => null,
                ],
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
            'actions' => [],
        ];

        $resourcesConfig = [
            'book' => [
                'config' => [
                    'query' => [
                        'collection' => 'ListQuery',
                    ],
                    'entity' => 'EntityClass',
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
                'config' => [
                    'query' => [
                        'collection' => 'ListQuery',
                    ],
                    'entity' => 'EntityClass',
                    'route' => ['path' => '/book'],
                    'translation_domain' => 'Book',
                    'name' => 'book',
                    'role' => null,
                    'actions' => [],
                ],
                'resource' => 'book',
                'actions' => [
                    'list' => [
                        'template' => 'list_template.html.twig',
                        'route' => ['path' => '/book', 'method' => ['get'], 'name' => 'book_list'],
                        'query' => 'ListQuery',
                        'resource' => 'book',
                        'action' => 'list',
                        'type' => 'list',
                        'collection' => true,
                        'entity' => 'EntityClass',
                        'role' => null,
                    ],
                    'edit' => [
                        'template' => 'edit_template.html.twig',
                        'route' => ['path' => '/book/:id/edit', 'method' => ['get', 'post'], 'name' => 'book_edit'],
                        'query' => 'ItemQuery',
                        'resource' => 'book',
                        'action' => 'edit',
                        'type' => 'edit',
                        'collection' => false,
                        'entity' => 'EntityClass',
                        'role' => null,
                    ],
                ],
            ],
        ];

        $this->validateConfigurationGuard($defaultConfig, $resourcesConfig);

        $processor = new ResourceConfigurationProcessor();
        $merged = $processor->process($defaultConfig, $resourcesConfig);

        $this->assertEquals($expected, $merged);
    }

    public function testFinalizeConfigActionConfigurationDataProvider()
    {
        return [
            'Empty actions' => [
                [],
                [],
                ['config' => ['actions' => []]],
            ],
            'Empty actions with default actions' => [
                ['page' => ['list' => []]],
                [],
                ['config' => ['actions' => ['page' => ['list' => ['role' => null, 'label' => 'List']]]]],
            ],
            'Disabled action' => [
                ['page' => ['list' => []]],
                ['config' => ['actions' => ['page' => ['list' => false]]]],
                ['config' => ['actions' => ['page' => []]]],
            ],
            'Empty default with resource config' => [
                ['page' => ['list' => []]],
                ['config' => ['actions' => ['page' => ['list' => ['label' => 'List Label', 'role' => 'LIST_ROLE', 'route' => 'list_route']]]]],
                ['config' => ['actions' => ['page' => ['list' => ['label' => 'List Label', 'role' => 'LIST_ROLE', 'route' => 'list_route']]]]],
            ],
            'Default config with resource config' => [
                ['page' => ['list' => ['role' => 'LIST_DEFAULT_ROLE']]],
                ['config' => ['actions' => ['page' => ['list' => ['label' => 'List Label', 'route' => 'list_route']]]]],
                ['config' => ['actions' => ['page' => ['list' => ['label' => 'List Label', 'role' => 'LIST_DEFAULT_ROLE', 'route' => 'list_route']]]]],
            ],
            'Nested role' => [
                ['page' => ['show' => [], 'edit' => ['nested' => 'show']]],
                [],
                ['config' => ['actions' => ['page' => ['show' => ['label' => 'Show', 'role' => null, 'nested' => ['edit' => ['label' => 'Edit', 'role' => null]]]]]]],
            ],
            'Route name from target action' => [
                ['page' => ['show' => [], 'list' => []]],
                ['config' => ['actions' => ['page' => ['list' => ['route' => ['name' => 'list_route']], 'show' => ['route' => ['name' => 'show_route']]]]]],
                [
                    'config' => ['actions' => ['page' => [
                        'show' => ['route' => 'show_route', 'role' => null, 'label' => 'Show'],
                        'list' => ['route' => 'list_route', 'role' => null, 'label' => 'List'],
                    ]]],
                ],
            ],
        ];
    }

    /**
     * @dataProvider testFinalizeConfigActionConfigurationDataProvider
     */
    public function testFinalizeConfigActionConfiguration(array $defaultActions, array $resource, array $expected)
    {
        $resourceConfigurationProcessor = new ResourceConfigurationProcessor();
        $resource = $resourceConfigurationProcessor->finalizeConfigActionConfiguration($resource, $defaultActions);

        $this->assertEquals($expected, $resource);
    }

    private function validateConfigurationGuard(array $resourcesConfig, array $resources)
    {
        $resourceConfigurationProcessor = new ResourceConfigurationProcessor();
        $config = $resourceConfigurationProcessor->process($resourcesConfig, $resources);

        $this->assertInternalType('array', $config);
    }
}
