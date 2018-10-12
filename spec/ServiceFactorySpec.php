<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\ServiceFactory;
use PhpSpec\ObjectBehavior;

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

    public function it_can_build_a_service_with_arguments()
    {
        $this::create(ServiceFactoryTestWithArguments::class, ['some_string', 123, ['an' => 'array']]);
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
