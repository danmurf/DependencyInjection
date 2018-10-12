<?php

namespace danmurf\DependencyInjection;

class ServiceFactory
{
    public static function create(string $class)
    {
        return new $class();
    }
}
