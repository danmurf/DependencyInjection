<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class AutoWireServiceLocator extends ConfigurableServiceLocator
{
    /**
     * Load services by infering their dependencies if they aren't specified in config.
     *
     * @param string             $id        The service ID or FQCN
     * @param ContainerInterface $container The container to get service dependencies from
     *
     * @throws NotFoundException
     * @throws ContainerException
     */
    public function locate($id, ContainerInterface $container)
    {
        try {
            return parent::locate($id, $container);
        } catch (NotFoundException $exception) {
            // Service isn't in config, so attempt to autowire...
        }

        try {
            $class = new ReflectionClass($id);
        } catch (ReflectionException $exception) {
            throw new NotFoundException(sprintf('Unable to autowire class `%s`.', $id));
        }

        if (null !== $class->getConstructor()) {
            return $this->instantiateClassWithConstructor($class, $container, $id);
        }

        return $class->newInstance();
    }

    /**
     * Get a dependency from a method parameter.
     *
     * @param ReflectionParameter $parameter
     * @param ContainerInterface  $container
     * @param string              $id
     *
     * @return mixed
     *
     * @throws ContainerException
     */
    private function getParameterInstance(
        ReflectionParameter $parameter,
        ContainerInterface $container,
        string $id
    ) {
        if (null === $dependencyClass = $parameter->getClass()) {
            throw new ContainerException(sprintf(
                'Unable to autowire service `%s` as it has scalar arguments.',
                $id
            ));
        }

        return $container->get($dependencyClass->getName());
    }

    /**
     * Get a new instance of a class with constructor arguments.
     *
     * @param ReflectionClass    $class
     * @param ContainerInterface $container
     * @param string             $id
     *
     * @return mixed
     *
     * @throws ContainerException
     */
    private function instantiateClassWithConstructor(
        ReflectionClass $class,
        ContainerInterface $container,
        string $id
    ) {
        $args = [];
        foreach ($class->getConstructor()->getParameters() as $parameter) {
            $args[] = $this->getParameterInstance($parameter, $container, $id);
        }

        return $class->newInstanceArgs($args);
    }
}
