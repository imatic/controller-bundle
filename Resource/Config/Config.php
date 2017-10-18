<?php
namespace Imatic\Bundle\ControllerBundle\Resource\Config;

class Config implements \Serializable
{
    public function serialize()
    {
        return serialize(get_object_vars($this));
    }

    public function unserialize($serialized)
    {
        $unserialized = unserialize($serialized, ['allowed_classes' => true]);

        foreach ($unserialized as $name => $value) {
            $this->{$name} = $value;
        }
    }

    public function copy()
    {
        return unserialize(serialize($this));
    }
}
