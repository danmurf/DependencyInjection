<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class AutoWireServiceLocator extends ConfigurableServiceLocator
{
    public function locate($id, ContainerInterface $container)
    {
        try {
            parent::locate($id, $container);
        } catch (NotFoundException $exception) {
            return new $id();
        }
    }
}
