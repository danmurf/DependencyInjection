<?php

namespace danmurf\DependencyInjection;

use ReflectionClass;

class ServiceFactory
{
    public static function create(string $className, $config = [])
    {
        $class = new ReflectionClass($className);
        $args = $this->getArgsFromConfig($className, $config);

        if (count($args) > 0) {
            return $class->newInstanceArgs($args);
        }

        return $class->newInstance();
    }

    private function getArgsFromConfig(string $className, array $config)
    {
        $args = [];

        if (isset($config[$className])) {
            foreach ($config[$className]['arguments'] as $argument) {
                $args[] = $argument['value'];
            }
        }

        return $args;
    }
}
