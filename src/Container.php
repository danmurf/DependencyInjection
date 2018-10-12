<?php

namespace danmurf\DependencyInjection;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private $instances = [];

    public function get($className)
    {
        if (!$this->hasInstance($className)) {
            $this->saveInstance(
                $this->buildInstance($className)
            );
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

    private function buildInstance(string $className)
    {
        return new $className();
    }

    private function saveInstance($instance)
    {
        $this->instances[get_class($instance)] = $instance;
    }
}
