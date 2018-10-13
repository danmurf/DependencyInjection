<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\ConfigurableServiceLocator;
use danmurf\DependencyInjection\ServiceLocatorInterface;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;

class ConfigurableServiceLocatorSpec extends ObjectBehavior
{
    public function let(ContainerInterface $container)
    {
        $this->beConstructedWith([]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigurableServiceLocator::class);
    }

    public function it_implements_service_locator_interface()
    {
        $this->shouldImplement(ServiceLocatorInterface::class);
    }

    public function it_can_return_a_new_instance_of_a_service_from_configuration(ContainerInterface $container)
    {
        $config = [
            'my.service.id' => [
                'class' => ConfigurableServiceLocatorTestClass::class,
            ],
        ];

        $this->beConstructedWith($config);

        $this->locate('my.service.id', $container)->shouldReturnAnInstanceOf(ConfigurableServiceLocatorTestClass::class);
    }

    public function it_can_return_a_new_instance_of_a_service_with_scalar_arguments_from_configuration(ContainerInterface $container)
    {
        $config = [
            'my.service.id' => [
                'class' => ConfigurableServiceLocatorTestClassWithArgs::class,
                'arguments' => [
                    [
                        'type' => 'scalar',
                        'value' => 123,
                    ],
                    [
                        'type' => 'scalar',
                        'value' => 'some_string',
                    ],
                ],
            ],
        ];

        $this->beConstructedWith($config);

        $this->locate('my.service.id', $container)->shouldReturnAnInstanceOf(ConfigurableServiceLocatorTestClassWithArgs::class);
    }
}

class ConfigurableServiceLocatorTestClass
{
}

class ConfigurableServiceLocatorTestClassWithArgs
{
    public function __construct(int $arg1, string $arg2)
    {
    }
}
