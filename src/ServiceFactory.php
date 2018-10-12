<?php

namespace danmurf\DependencyInjection;

use ReflectionClass;

class ServiceFactory
{
    public static function create(string $className, $args = [])
    {
        $class = new ReflectionClass($className);

        if (count($args) > 0) {
            return $class->newInstanceArgs($args);
        }

        return $class->newInstance();
    }
}
