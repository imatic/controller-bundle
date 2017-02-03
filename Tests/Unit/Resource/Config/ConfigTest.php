<?php

namespace Imatic\Bundle\ControllerBundle\Tests\Resource;

use Imatic\Bundle\ControllerBundle\Resource\Config\AccessTrait;
use Imatic\Bundle\ControllerBundle\Resource\Config\Config as BaseConfig;
use Imatic\Bundle\ControllerBundle\Resource\Config\ResourceConfig;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializable()
    {
        $a = [1, true, new ResourceConfig(['x', false, 'y'])];
        $b = 123;
        $c = 'Hello';

        $config = new Config($a, $b, $c);

        $copy = $config->copy();

        $this->assertEquals($a, $copy->a);
        $this->assertEquals($b, $copy->b);
        $this->assertEquals($c, $copy->c);

        // Deep copy test
        $this->assertNotSame($copy, $config);
        $this->assertInstanceOf(ResourceConfig::class, $copy->a[2]);
        $this->assertEquals($copy->a[2], $config->a[2]);
        $this->assertNotSame($copy->a[2], $config->a[2]);
    }

    public function testAccessTrait()
    {
        $config = new ConfigAccess(['a' => 'a', 'b' => true, 'c' => [1, 12]]);

        // Has
        $this->assertTrue(isset($config['a']));
        $this->assertTrue(isset($config->a));

        $this->assertFalse(isset($config['x']));
        $this->assertFalse(isset($config->x));

        // Get
        $this->assertEquals('a', $config['a']);
        $this->assertEquals(true, $config['b']);
        $this->assertEquals([1, 12], $config['c']);

        $this->assertEquals('a', $config->a);
        $this->assertEquals(true, $config->b);
        $this->assertEquals([1, 12], $config->c);

        // Set
        $config->aa = 'aaVal';
        $config['bb'] = 'bbVal';
        $this->assertEquals('aaVal', $config['aa']);
        $this->assertEquals('bbVal', $config['bb']);

        // Unset
        unset($config->aa, $config['bb']);
        $this->assertFalse(isset($config['aa']));
        $this->assertFalse(isset($config->bb));
    }
}

class Config extends BaseConfig
{
    public $a;
    public $b;
    public $c;

    public function __construct(array $a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
}

class ConfigAccess extends BaseConfig implements \ArrayAccess
{
    use AccessTrait;

    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}