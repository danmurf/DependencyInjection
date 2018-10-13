<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class ConfigurableServiceLocator implements ServiceLocatorInterface
{
    const ARGUMENT_TYPE_SCALAR = 'scalar';
    const ARGUMENT_TYPE_SERVICE = 'service';

    const ARGUMENT_TYPES = [
            self::ARGUMENT_TYPE_SCALAR,
            self::ARGUMENT_TYPE_SERVICE,
        ];

    private $config;

    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->config = $config;
    }

    /**
     * Get a new instance of a service.
     *
     * @param string             $id        the service id
     * @param ContainerInterface $container the container to get services dependencies from
     *
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function locate(string $id, ContainerInterface $container)
    {
        if (!isset($this->config[$id])) {
            throw new NotFoundException(sprintf('Unable to locate service `%s` from configuration.', $id));
        }

        $class = new ReflectionClass($this->config[$id]['class']);

        if (!isset($this->config[$id]['arguments'])) {
            return $class->newInstance();
        }

        $args = [];
        foreach ($this->config[$id]['arguments'] as $argumentConfig) {
            switch ($argumentConfig['type']) {
                case self::ARGUMENT_TYPE_SCALAR:
                    $args[] = $argumentConfig['value'];
                    break;

                case self::ARGUMENT_TYPE_SERVICE:
                    $args[] = $container->get($argumentConfig['value']);
                    break;
            }
        }

        return $class->newInstanceArgs($args);
    }

    /**
     * Ensure the config is in a valid format.
     *
     * @param array $config
     *
     * @throws ContainerException
     */
    private function validateConfig(array $config)
    {
        foreach ($config as $id => $service) {
            if (!isset($service['class'])) {
                throw new ContainerException(sprintf('Configured service `%s` has no `class` value.', $id));
            }

            if (isset($service['arguments'])) {
                foreach ($service['arguments'] as $argument) {
                    if (!isset($argument['type']) || !isset($argument['value'])) {
                        throw new ContainerException(sprintf(
                            'Configuration for service `%s` must have `type` and `value` values.',
                            $id
                        ));
                    }

                    if (false === array_search($argument['type'], self::ARGUMENT_TYPES)) {
                        throw new ContainerException(sprintf(
                            'Unknown argument type `%s` for service `%s`. Accepted values are `scalar` and `service`.',
                            (string) $argument['type'],
                            $id
                        ));
                    }
                }
            }
        }
    }
}
