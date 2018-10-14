<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Simple dependency injection container with configurable service
 * location strategies.
 *
 * @author Dan Murfitt <dan@murfitt.net>
 */
class Container implements ContainerInterface
{
    /** @var ServiceLocatorInterface */
    private $serviceLocator;

    /** @var array */
    private $instances = [];

    /** @var array */
    private $classMap = [];

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get an instance of a service.
     *
     * @param string $requestedId Registered service ID or FQCN
     *
     * @throws NotFoundException
     */
    public function get($requestedId)
    {
        if (false === $registeredId = $this->getRegisteredId($requestedId)) {
            $this->register($this->serviceLocator->locate($requestedId, $this), $requestedId);
            $registeredId = $requestedId;
        }

        return $this->instances[$registeredId];
    }

    /**
     * Determine if the container can locate an instance of the specified service ID or FQCN.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        if (false !== $this->getRegisteredId($id)) {
            return true;
        }

        try {
            $this->serviceLocator->locate($id, $this);
        } catch (NotFoundException $exception) {
            return false;
        }

        return true;
    }

    /**
     * Register a service with the container.
     *
     * @param mixed       $instance An instance of a service
     * @param string|null $id       A specified service ID (FQCN will be used in the case of `null`)
     */
    public function register($instance, string $id = null)
    {
        if (!is_object($instance)) {
            throw new ContainerException('Only objects can be registered with the container.');
        }

        $class = get_class($instance);

        if (null === $id) {
            $id = $class;
        }

        $this->instances[$id] = $instance;
        $this->classMap[$class] = $id;
    }

    /**
     * Get the registered ID for the requested service ID or FQCN.
     *
     * @param string $requestedId Service id or FQCN
     *
     * @return string|bool The registered ID, or false it's not found
     */
    private function getRegisteredId(string $requestedId)
    {
        if (isset($this->instances[$requestedId])) {
            return $requestedId;
        }

        if (isset($this->classMap[$requestedId])) {
            return $this->classMap[$requestedId];
        }

        return false;
    }
}
