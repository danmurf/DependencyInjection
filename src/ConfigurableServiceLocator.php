<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class ConfigurableServiceLocator implements ServiceLocatorInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function locate(string $id, ContainerInterface $container)
    {
        if (!isset($this->config[$id])) {
            throw new NotFoundException('Unable to locate service from configuration.');
        }

        if (!isset($this->config[$id]['class'])) {
            throw new ContainerException(sptintf('Configured service `%s` has no `class` value.', $id));
        }

        $class = new ReflectionClass($this->config[$id]['class']);

        $instance = $class->newInstance();

        return $instance;
    }
}
