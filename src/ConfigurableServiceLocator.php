<?php

namespace danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\CircularReferenceException;
use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

/**
 * Service locator which can get find and build services using
 * pre-defined configuration.
 *
 * @author Dan Murfitt <dan@murfitt.net>
 */
class ConfigurableServiceLocator implements ServiceLocatorInterface
{
    const ARGUMENT_TYPE_SCALAR = 'scalar';
    const ARGUMENT_TYPE_SERVICE = 'service';

    const ARGUMENT_TYPES = [
            self::ARGUMENT_TYPE_SCALAR,
            self::ARGUMENT_TYPE_SERVICE,
        ];

    /** @var array */
    private $config = [];

    /** @var array */
    private $locateCalls = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->config = $config;
    }

    /**
     * Get a new instance of a service.
     *
     * @param string             $id        The service id
     * @param ContainerInterface $container The container to get service dependencies from
     *
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function locate($id, ContainerInterface $container)
    {
        $this->validateRequest($id);

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
        foreach ($config as $id => $definition) {
            if (isset($definition['class'])) {
                $this->validateClassDefinition($definition, $id);

                return;
            }

            if (isset($definition['interface'])) {
                $this->validateInterfaceDefinition($definition, $id);

                return;
            }

            throw new ContainerException(sprintf('Configured service `%s` has no `class` or `interface` value.', $id));
        }
    }

    /**
     * Ensure a class definied service has valid config.
     *
     * @param array  $definition
     * @param string $id
     */
    private function validateClassDefinition(array $definition, string $id)
    {
        if (isset($definition['arguments'])) {
            $this->validateArguments($definition['arguments'], $id);
        }
    }

    /**
     * Ensure an interface defined service has valid config.
     *
     * @param array  $definition
     * @param string $id
     */
    private function validateInterfaceDefinition(array $definition, string $id)
    {
        if (!isset($definition['service'])) {
            throw new ContainerException(sprintf('Configured interface definition `%s` has no `service` value.', $id));
        }
    }

    /**
     * Ensure the arguments config is in a valid format.
     *
     * @param array  $arguments
     * @param string $id
     *
     * @throws ContainerException
     */
    private function validateArguments(array $arguments, string $id)
    {
        foreach ($arguments as $argument) {
            $this->validateArgumentProperties($argument, $id);
            $this->validateArgumentType($argument['type'], $id);
        }
    }

    /**
     * Ensure the argument has valid properties.
     *
     * @param array  $argument
     * @param string $id
     *
     * @throws ContainerException
     */
    private function validateArgumentProperties(array $argument, string $id)
    {
        if (!isset($argument['type']) || !isset($argument['value'])) {
            throw new ContainerException(sprintf(
                'Configuration for service `%s` must have `type` and `value` values.',
                $id
            ));
        }
    }

    /**
     * Ensure the argument type is valid.
     *
     * @param string $type
     * @param string $id
     *
     * @throws ContainerException
     */
    private function validateArgumentType(string $type, string $id)
    {
        if (false === array_search($type, self::ARGUMENT_TYPES)) {
            throw new ContainerException(sprintf(
                'Unknown argument type `%s` for service `%s`. Accepted values are `scalar` and `service`.',
                $type,
                $id
            ));
        }
    }

    /**
     * @param string $id
     *
     * @throws CircularReferenceException
     */
    private function validateRequest(string $id)
    {
        if (false !== array_search($id, $this->locateCalls)) {
            throw new CircularReferenceException(sprintf('Circular dependency reference detected for `%s`', $id));
        }

        $this->locateCalls[] = $id;
    }
}
