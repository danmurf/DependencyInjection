<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\AutoWireServiceLocator;
use danmurf\DependencyInjection\ConfigurableServiceLocator;
use PhpSpec\ObjectBehavior;

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
}
