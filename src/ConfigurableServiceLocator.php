<?php

namespace danmurf\DependencyInjection;

use Psr\Container\ContainerInterface;

class ConfigurableServiceLocator implements ServiceLocatorInterface
{
    public function locate(string $id, ContainerInterface $container)
    {
    }
}
