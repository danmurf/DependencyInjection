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
     * @param string $id registered service ID or FQCN
     *
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (!$this->hasInstance($id)) {
            $this->register($this->serviceLocator->locate($id, $this), $id);
        }

        return $this->instances[$id];
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
        if ($this->hasInstance($id)) {
            return true;
        }

        try {
            $this->register($this->serviceLocator->locate($id, $this), $id);
        } catch (NotFoundException $exception) {
            return false;
        }

        return false;
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

        if (null === $id) {
            $id = get_class($instance);
        }

        $this->instances[$id] = $instance;
    }

    /**
     * Determine if the container has a registered instance of the service.
     *
     * @param string $id service id or FQCN
     *
     * @return bool
     */
    private function hasInstance(string $id): bool
    {
        return isset($this->instances[$id]);
    }
}
