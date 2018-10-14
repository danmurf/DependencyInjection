<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Service locator which can be injected into the container so that
 * only service which have been registered can be located.
 */
class RegistrationOnlyServiceLocator implements ServiceLocatorInterface
{
    /**
     * Never attempt to locate a service.
     *
     * @param string             $id
     * @param ContainerInterface $container
     *
     * @throws NotFoundException
     */
    public function locate($id, ContainerInterface $container)
    {
        throw new NotFoundException(sprintf('Service `%s` has not been registered with the container.', $id));
    }
}
