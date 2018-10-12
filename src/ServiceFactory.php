<?php

namespace danmurf\DependencyInjection;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class ServiceFactory
{
    public static function create(string $className, $config = [], ContainerInterface $container = null)
    {
        $class = new ReflectionClass($className);
        $args = $this->getArgsFromConfig($className, $config, $container);

        if (count($args) > 0) {
            return $class->newInstanceArgs($args);
        }

        return $class->newInstance();
    }

    private function getArgsFromConfig(string $className, array $config, ContainerInterface $container = null)
    {
        $args = [];

        if (isset($config[$className])) {
            foreach ($config[$className]['arguments'] as $argument) {
                switch ($argument['type']) {
                    case 'scalar':
                        $args[] = $argument['value'];
                        break;

                    case 'service':
                        $args[] = $container->get($argument['value']);
                        break;
                }
            }
        }

        return $args;
    }
}
