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
        $this::create(ServiceFactoryTestClass::class)->shouldReturnAnInstanceOf(ServiceFactoryTestClass::class);
    }
}

class ServiceFactoryTestClass
{
}
