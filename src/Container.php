<?php

namespace danmurf\DependencyInjection;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private $instances = [];

    public function get($className)
    {
        if (!$this->hasInstance($className)) {
            $this->store($this->build($className));
        }

        return $this->instances[$className];
    }

    public function has($className): bool
    {
    }

    private function hasInstance(string $className): bool
    {
        return isset($this->instances[$className]);
    }

    private function build(string $className)
    {
        return new $className();
    }

    private function store($instance)
    {
        $this->instances[get_class($instance)] = $instance;
    }
}
