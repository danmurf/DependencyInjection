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
            throw new NotFoundException(sprintf('Unable to locate service `%s` from configuration.', $id));
        }

        if (!isset($this->config[$id]['class'])) {
            throw new ContainerException(sptintf('Configured service `%s` has no `class` value.', $id));
        }

        $class = new ReflectionClass($this->config[$id]['class']);

        if (!isset($this->config[$id]['arguments'])) {
            return $class->newInstance();
        }

        $args = [];
        foreach ($this->config[$id]['arguments'] as $argumentConfig) {
            switch ($argumentConfig['type']) {
                case 'scalar':
                    $args[] = $argumentConfig['value'];
                    break;

                case 'service':
                    $args[] = $this->locate($argumentConfig['value'], $container);
                    break;

                default:
                    throw new ContainerException(sprintf('Unknown argument type `%s`. Accepted values are `scalar` and `service`.', $argumentConfig['type']));
            }
        }

        return $class->newInstanceArgs($args);
    }
}
