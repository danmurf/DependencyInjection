<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\Exception\NotFoundException;
use danmurf\DependencyInjection\RegistrationOnlyServiceLocator;
use danmurf\DependencyInjection\ServiceLocatorInterface;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;

class RegistrationOnlyServiceLocatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(RegistrationOnlyServiceLocator::class);
    }

    public function it_implements_service_locator_interface()
    {
        $this->shouldImplement(ServiceLocatorInterface::class);
    }

    public function it_throws_not_found_exception_when_attempting_to_locate_a_service(
        ContainerInterface $container
    ) {
        $this->shouldThrow(NotFoundException::class)->during('locate', ['my.service', $container]);
    }
}
