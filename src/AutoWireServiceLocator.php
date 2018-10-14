<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class AutoWireServiceLocator extends ConfigurableServiceLocator
{
    public function locate($id, ContainerInterface $container)
    {
        try {
            parent::locate($id, $container);
        } catch (NotFoundException $exception) {
        }

        try {
            $class = new ReflectionClass($id);
        } catch (ReflectionException $exception) {
            throw new NotFoundException(sprintf('Unable to autowire class `%s`.', $id));
        }

        if (null !== $constructor = $class->getConstructor()) {
            $args = [];
            foreach ($constructor->getParameters() as $parameter) {
                if (null === $dependencyClass = $parameter->getClass()) {
                    throw new ContainerException(sprintf('Unable to autowire service `%s` as it has scalar arguments.', $id));
                }

                $args[] = $container->get($dependencyClass->getName());
            }

            return $class->newInstanceArgs($args);
        }

        return $class->newInstance();
    }
}
