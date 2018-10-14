<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\AutoWireServiceLocator;
use danmurf\DependencyInjection\ConfigurableServiceLocator;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;

class AutoWireServiceLocatorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AutoWireServiceLocator::class);
    }

    public function it_extends_the_configurable_service_locator()
    {
        $this->shouldBeAnInstanceOf(ConfigurableServiceLocator::class);
    }

    public function it_can_locate_a_service_without_configuration(ContainerInterface $container)
    {
        $this->locate(TestAutoWireService::class, $container)
            ->shouldReturnAnInstanceOf(TestAutoWireService::class);
    }

    public function it_can_locate_a_service_with_dependencies(ContainerInterface $container)
    {
        $container->get(TestAutoWireService::class)->willReturn(new TestAutoWireService());

        $this->locate(TestAutoWireServiceWithDependencies::class, $container)
            ->shouldReturnAnInstanceOf(TestAutoWireServiceWithDependencies::class);

        $container->get(TestAutoWireService::class)->shouldHaveBeenCalled();
    }
}

class TestAutoWireService
{
}

class TestAutoWireServiceWithDependencies
{
    public function __construct(TestAutoWireService $dependency)
    {
    }
}
