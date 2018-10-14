<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class RegistrationOnlyServiceLocator implements ServiceLocatorInterface
{
    public function locate($id, ContainerInterface $container)
    {
        throw new NotFoundException(sprintf('Unable to locate service `%s` using registration only strategy.', $id));
    }
}
