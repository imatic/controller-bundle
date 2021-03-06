<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Tests\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\Resource;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceAction;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceConfig;
use Imatic\Bundle\ControllerBundle\Resource\ConfigurationProcessor;
use PHPUnit\Framework\TestCase;

class ConfigurationProcessorTest extends TestCase
{
    public function testProcessResources()
    {
        $prototype = [
            'config' => [
            ],
            'actions' => [
                'list' => [
                    'route' => ['path' => '/'],
                    'group' => 'list',
                ],
                'show' => [
                    'route' => ['path' => '/{id}'],
                    'group' => 'item',
                ],
            ],
        ];

        $resources = [
            'app_book' => [
                'config' => [
                    'entity' => 'BookEntity',
                ],
                'actions' => [
                    'list' => [],
                    'linked' => ['target' => 'app_user:show'],
                ],
            ],
            'app_user' => [
                'config' => [
                    'entity' => 'UserEntity',
                ],
                'actions' => [
                    'list' => null,
                    'show' => ['role' => 'SOME_ROLE'],
                ],
            ],
        ];

        $processor = new ConfigurationProcessor();
        $processed = $processor->processResources($resources, $prototype);

        $this->assertIsArray($processed);
        $this->assertCount(2, $processed);
        $this->assertInstanceOf(Resource::class, $processed['app_book']);
        $this->assertInstanceOf(Resource::class, $processed['app_user']);

        // Linked action
        $linkedAction = $processed['app_book']->getAction('linked');
        $this->assertEquals('SOME_ROLE', $linkedAction['role']);
        $this->assertEquals($processed['app_user']->getAction('show'), $linkedAction['target']);
    }

    /**
     * @dataProvider processResourceDataProvider
     */
    public function testProcessResource($prototype, $resource, Resource $expected)
    {
        $processor = new ConfigurationProcessor();
        $processed = $processor->processResource($resource, $prototype);

        $this->assertEquals($expected, $processed);
    }

    public function processResourceDataProvider()
    {
        return [
            // --------------------------------------------
            'Empty configuration 1' => [
                [
                    'config' => [
                    ],
                    'actions' => [
                    ],
                ],
                [
                    'config' => [
                    ],
                    'name' => 'app_book',
                    'actions' => [
                    ],
                ],
                new Resource(
                    [],
                    new ResourceConfig([
                        'route' => ['path' => '/app/book'],
                        'translation_domain' => 'AppBook',
                        'role' => null,
                    ]),
                    'app_book'
                ),
            ],
            // --------------------------------------------
            'Basic configuration 1' => [
                [
                    'config' => [
                    ],
                    'actions' => [
                        'list' => [
                            'route' => ['path' => '/'],
                            'group' => 'list',
                        ],
                        'show' => [
                            'route' => ['path' => '/{id}'],
                            'group' => 'item',
                        ],
                    ],
                ],
                [
                    'config' => [
                        'entity' => 'BookEntity',
                    ],
                    'name' => 'app_book',
                    'actions' => [
                        'list' => [],
                    ],
                ],
                new Resource(
                    ['list' => new ResourceAction([
                        'route' => ['path' => '/app/book', 'name' => 'app_book_list'],
                        'group' => 'list',
                        'type' => 'list',
                        'name' => 'list',
                        'entity' => 'BookEntity',
                        'role' => null,
                    ])],
                    new ResourceConfig([
                        'entity' => 'BookEntity',
                        'route' => ['path' => '/app/book'],
                        'translation_domain' => 'AppBook',
                        'role' => null,
                    ]),
                    'app_book'
                ),
            ],
            // --------------------------------------------
            'Basic configuration 2' => [
                [
                    'config' => [
                    ],
                    'actions' => [
                        'list' => [
                            'route' => ['path' => '/'],
                            'group' => 'list',
                        ],
                        'show' => [
                            'route' => ['path' => '/{id}'],
                            'group' => 'item',
                        ],
                    ],
                ],
                [
                    'config' => [
                        'entity' => 'BookEntity',
                    ],
                    'name' => 'app_book',
                    'actions' => [
                        'list' => [],
                        'show' => false,
                    ],
                ],
                new Resource(
                    ['list' => new ResourceAction([
                        'route' => ['path' => '/app/book', 'name' => 'app_book_list'],
                        'group' => 'list',
                        'type' => 'list',
                        'name' => 'list',
                        'entity' => 'BookEntity',
                        'role' => null,
                    ])],
                    new ResourceConfig([
                        'entity' => 'BookEntity',
                        'route' => ['path' => '/app/book'],
                        'translation_domain' => 'AppBook',
                        'role' => null,
                    ]),
                    'app_book'
                ),
            ],
            // --------------------------------------------
        ];
    }

    public function testArrayMap()
    {
        $array = ['a' => '1', 'b' => '2'];
        $fn = function ($value, $key) {
            return $value . $key;
        };
        $expected = ['a' => '1a', 'b' => '2b'];

        $this->assertEquals($expected, ConfigurationProcessor::arrayMap($fn, $array));
    }
}
