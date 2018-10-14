<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\Container;
use danmurf\DependencyInjection\ContainerFactory;
use PhpSpec\ObjectBehavior;

class ContainerFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ContainerFactory::class);
    }

    public function it_can_return_a_container_with_registration_only_strategy()
    {
        $this::createRegistrationOnlyContainer()
            ->shouldReturnAnInstanceOf(Container::class);
    }

    public function it_can_return_a_container_with_configuration_strategy()
    {
        $config = [];
        $this::createConfigurableContainer($config)
            ->shouldReturnAnInstanceOf(Container::class);
    }

    public function it_can_return_a_container_with_auto_wire_strategy()
    {
        $config = [];
        $this::createAutoWireContainer($config)
            ->shouldReturnAnInstanceOf(Container::class);
    }
}
