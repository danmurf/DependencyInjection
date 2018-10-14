<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class AutoWireServiceLocator extends ConfigurableServiceLocator
{
    public function locate($id, ContainerInterface $container)
    {
        try {
            parent::locate($id, $container);
        } catch (NotFoundException $exception) {
            $class = new ReflectionClass($id);

            if (null !== $constructor = $class->getConstructor()) {
                $args = [];
                foreach ($constructor->getParameters() as $parameter) {
                    $args[] = $container->get($parameter->getClass()->getName());
                }

                return $class->newInstanceArgs($args);
            }

            return $class->newInstance();
        }
    }
}
