<?php

namespace danmurf\DependencyInjection;

use Psr\Container\ContainerInterface;

interface ServiceLocatorInterface
{
    /**
     * Method used to return a new instance of a service.
     *
     * @param string $id The service's configured ID
     * @param ContainerInterface A container where dependencies for this service can be found
     *
     * @return mixed the service instance
     *
     * @throws danmurf\DependencyInjection\Exception\NotFoundException
     */
    public function locate(string $id, ContainerInterface $container);
}
