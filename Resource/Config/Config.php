<?php declare(strict_types=1);
namespace Imatic\Bundle\ControllerBundle\Resource\Config;

class Config
{
    public function __serialize(): array
    {
        return \get_object_vars($this);
    }

    public function __unserialize(array $data): void
    {
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
    }

    public static function fromSerialized(string $data): static
    {
        return \unserialize($data, ['allowed_classes' => true]);
    }

    public function copy()
    {
        return \unserialize(\serialize($this));
    }
}
