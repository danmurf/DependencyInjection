<?php

namespace danmurf\DependencyInjection;

use Psr\Container\ContainerInterface;

interface ServiceLocatorInterface
{
    /**
     * Method used to return an instance of a service. The container can be passed in
     * to resolve any dependencies the service may have.
     *
     * @param string $id The service's configured ID
     * @param ContainerInterface|null A container where dependencies for this service can be found
     *
     * @return mixed the service instance
     *
     * @throws danmurf\DependencyInjection\Exception\NotFoundException
     */
    public function locate(string $id, ContainerInterface $container = null);
}
