<?php

namespace Imatic\Bundle\ControllerBundle\Controller\Resource;

/**
 * Experimental config class.
 * Not in use now, only concept.
 *
 * For typed configuration.
 *
 * todo:
 * - zvlast repository, kazda dostane vlastni konfiguraci (nebudou se pak zbytecne nacitat a deserializovat ostatni konfigurace)
 * - jedna hlavni pro route loader, debug apod, ktera bude mit ostatni repository jako zavislosti (lazy)
 */
class Config implements \Serializable, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function serialize()
    {
        return serialize($this->config);
    }

    public function setIn(array $path, $value)
    {
        return static::rec($this->config, $path, 'set', $value);
    }

    public function getIn(array $path, $default = null)
    {
        return static::rec($this->config, $path, 'get', $default);
    }

    public function removeIn(array $path)
    {
        return static::rec($this->config, $path, 'remove');
    }

    public function hasIn(array $path)
    {
        return static::rec($this->config, $path, 'has');
    }

    public function unserialize($serialized)
    {
        $this->config = unserialize($serialized, ['allowed_classes' => true]);
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->config);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    public function __unset($name)
    {
        return $this->offsetUnset($name);
    }

    public static function rec(&$data, $path, $op, $value = null)
    {
        $key = array_shift($path);

        if (isset($data[$key])) {
            if (0 === count($path)) {

                switch ($op) {
                    case 'set':
                        $data[$key] = $value;
                        break;
                    case 'get':
                        return $data[$key];
                        break;
                    case 'has':
                        return true;
                        break;
                    case 'remove':
                        $ret = $data[$key];
                        unset($data[$key]);

                        return $ret;
                        break;
                }

            } else {
                return static::rec($data[$key], $path, $op, $value);
            }
        } else {
            switch ($op) {
                case 'get':
                    return $value;
                    break;
                case 'has':
                    return false;
                    break;
            }
        }
    }
}

// Resources base config

class ResourcesConfig extends Config
{
}

class ResourcesDefault extends Config
{
}

class ResourcesActions extends Config
{
}

// Resources

class Resources extends Config
{
}


class Resource extends Config
{
}

class ResourceAction extends Config
{
}

class ResourceConfig extends Config
{
}
