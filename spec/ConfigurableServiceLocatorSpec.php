<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\ConfigurableServiceLocator;
use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
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

    public function it_can_return_a_new_instance_of_a_service_with_service_arguments_from_configuration(ContainerInterface $container)
    {
        $config = [
            'my.service.dependency' => [
                'class' => ConfigurableServiceLocatorTestClass::class,
            ],
            'my.service.id' => [
                'class' => ConfigurableServiceLocatorTestClassWithServiceArgs::class,
                'arguments' => [
                    [
                        'type' => 'service',
                        'value' => 'my.service.dependency',
                    ],
                ],
            ],
        ];

        $this->beConstructedWith($config);

        $container->get('my.service.dependency')->willReturn(new ConfigurableServiceLocatorTestClass());

        $this->locate('my.service.id', $container)->shouldReturnAnInstanceOf(ConfigurableServiceLocatorTestClassWithServiceArgs::class);

        // Dependencies should be resolved from the container
        $container->get('my.service.dependency')->shouldHaveBeenCalled();
    }

    public function it_throws_a_container_exception_when_a_configured_service_has_no_class(ContainerInterface $container)
    {
        $config = [
            'my.service.dependency' => [],
        ];

        $this->beConstructedWith($config);

        $this->shouldThrow(ContainerException::class)->duringInstantiation();
    }

    public function it_throws_a_not_found_exception_when_trying_to_location_a_service_which_hasnt_been_configured(ContainerInterface $container)
    {
        $this->shouldThrow(NotFoundException::class)->during('locate', ['non.existant.service', $container]);
    }

    public function it_throws_a_container_exception_when_an_unknown_argument_type_is_encountered(ContainerInterface $container)
    {
        $config = [
            'my.broken.service' => [
                'class' => ConfigurableServiceLocatorTestClass::class,
                'arguments' => [
                    [
                        'type' => 'invalid',
                        'value' => 'some_value',
                    ],
                ],
            ],
        ];

        $this->beConstructedWith($config);

        $this->shouldThrow(ContainerException::class)->duringInstantiation();
    }

    public function it_throws_a_container_exception_if_an_argument_doesnt_have_a_type_and_value(ContainerInterface $container)
    {
        $config = [
            'my.broken.service' => [
                'class' => ConfigurableServiceLocatorTestClass::class,
                'arguments' => [
                    [
                        'broken' => 'foo',
                        'invalid' => 'bar',
                    ],
                ],
            ],
        ];

        $this->beConstructedWith($config);

        $this->shouldThrow(ContainerException::class)->duringInstantiation();
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

class ConfigurableServiceLocatorTestClassWithServiceArgs
{
    public function __construct(ConfigurableServiceLocatorTestClass $dependency)
    {
    }
}
