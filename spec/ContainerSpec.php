<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\Container;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;

class ContainerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Container::class);
    }

    public function it_implements_container_interface()
    {
        $this->shouldImplement(ContainerInterface::class);
    }

    public function it_can_instansiate_a_new_instance()
    {
        $this->get(ContainerTestClass::class)->shouldReturnAnInstanceOf(ContainerTestClass::class);
    }

    public function it_always_returns_the_same_instance()
    {
        $testClass = $this->get(ContainerTestClass::class);
        $this->get(ContainerTestClass::class)->shouldReturn($testClass);
    }
}

class ContainerTestClass
{
}
