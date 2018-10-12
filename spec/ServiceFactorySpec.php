<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\ServiceFactory;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;

class ServiceFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ServiceFactory::class);
    }

    public function it_can_build_a_basic_service()
    {
        $this::create(ServiceFactoryTest::class)->shouldReturnAnInstanceOf(ServiceFactoryTest::class);
    }

    public function it_can_build_a_service_with_configured_arguments()
    {
        $config = [
            ServiceFactoryTestWithArguments::class => [
                'arguments' => [
                    [
                        'type' => 'scalar',
                        'value' => 'some_string',
                    ],
                    [
                        'type' => 'scalar',
                        'value' => 123,
                    ],
                    [
                        'type' => 'scalar',
                        'value' => [
                            'an' => 'array',
                        ],
                    ],
                ],
            ],
        ];

        $this::create(ServiceFactoryTestWithArguments::class, $config)
        ->shouldReturnAnInstanceOf(ServiceFactoryTestWithArguments::class);
    }

    public function it_can_build_a_service_with_a_configured_service_argument(
        ContainerInterface $container
    ) {
        $config = [
            ServiceFactoryTestWithServiceArgument::class => [
                'arguments' => [
                    [
                        'type' => 'service',
                        'value' => ServiceFactoryTest::class,
                    ],
                ],
            ],
        ];

        $serviceFactoryTest = new ServiceFactoryTest();
        $container->get(ServiceFactoryTest::class)->willReturn($serviceFactoryTest);

        $this::create(ServiceFactoryTestWithServiceArgument::class, $config, $container)
            ->shouldReturnAnInstanceOf(ServiceFactoryTestWithServiceArgument::class);
    }
}

class ServiceFactoryTest
{
}

class ServiceFactoryTestWithArguments
{
    public function __construct(string $someString, int $someInt, array $someArray)
    {
    }
}

class ServiceFactoryTestWithServiceArgument
{
    public function __construct(ServiceFactoryTest $serviceFactoryTest)
    {
    }
}
