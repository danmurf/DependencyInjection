<?php

namespace danmurf\DependencyInjection;

class ContainerFactory
{
    /**
     * Create a new container which requires all services are manually registered.
     *
     * @return Container
     */
    public static function createRegistrationOnlyContainer()
    {
        return new Container(new RegistrationOnlyServiceLocator());
    }

    /**
     * Create a new container which requires services are specified in config.
     *
     * @param array $config
     *
     * @return Container
     */
    public function createConfigurableContainer(array $config)
    {
        return new Container(new ConfigurableServiceLocator($config));
    }

    /**
     * Create a new container which loads services from the specified config, and
     * if they're not included, attempts to load them automatically.
     *
     * @param array $config
     *
     * @return Container
     */
    public function createAutoWireContainer(array $config)
    {
        return new Container(new AutoWireServiceLocator($config));
    }
}
