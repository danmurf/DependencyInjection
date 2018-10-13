<?php

namespace danmurf\DependencyInjection;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

interface ServiceLocatorInterface
{
    /**
     * Return a new instance of a service.
     *
     * @param string $id the service's configured ID
     * @param ContainerInterface a container where dependencies for this service can be found
     *
     * @return mixed The service instance
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function locate($id, ContainerInterface $container);
}
