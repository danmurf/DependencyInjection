<?php

namespace spec\danmurf\DependencyInjection;

use danmurf\DependencyInjection\Container;
use danmurf\DependencyInjection\Exception\ContainerException;
use danmurf\DependencyInjection\Exception\NotFoundException;
use danmurf\DependencyInjection\ServiceLocatorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;

class ContainerSpec extends ObjectBehavior
{
    public function let(
        ServiceLocatorInterface $serviceLocator,
        ContainerInterface $container
    ) {
        $serviceLocator->locate(Argument::type('string'), Argument::type(ContainerInterface::class))
            ->willThrow(NotFoundException::class);

        $this->beConstructedWith($serviceLocator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Container::class);
    }

    public function it_implements_container_interface()
    {
        $this->shouldImplement(ContainerInterface::class);
    }

    public function it_can_register_services()
    {
        $service = new ContainerTestClass();

        $this->register($service, 'my.service');

        $this->get('my.service')->shouldReturn($service);
    }

    public function it_can_register_a_service_and_infer_the_id()
    {
        $service = new ContainerTestClass();

        $this->register($service);

        $this->get(ContainerTestClass::class)->shouldReturn($service);
    }

    public function it_throws_a_container_exception_if_trying_to_register_a_service_which_isnt_an_object()
    {
        $this->shouldThrow(ContainerException::class)->during('register', ['a string']);
    }

    public function it_can_use_the_service_locator_to_self_register_an_instance(
        ServiceLocatorInterface $serviceLocator
    ) {
        $service = new ContainerTestClass();
        $serviceLocator->locate(ContainerTestClass::class, $this)->willReturn($service);

        $this->get(ContainerTestClass::class)->shouldReturn($service);

        $serviceLocator->locate(ContainerTestClass::class, $this)->shouldHaveBeenCalled();
    }

    public function it_can_determine_if_it_has_an_instance()
    {
        $service = new ContainerTestClass();

        $this->register($service);

        $this->has(ContainerTestClass::class)->shouldReturn(true);
    }

    public function it_can_determine_if_it_is_able_to_locate_a_service(
        ServiceLocatorInterface $serviceLocator
    ) {
        $instance = new \stdClass();

        $serviceLocator->locate('found.service', $this)->willReturn($instance);

        $this->has('found.service')->shouldReturn(true);
    }

    public function it_can_determine_if_it_is_unable_to_locate_a_service(
        ServiceLocatorInterface $serviceLocator
    ) {
        $serviceLocator->locate('not.found', $this)->willThrow(NotFoundException::class);

        $this->has('not.found')->shouldReturn(false);
    }

    public function it_passes_through_a_not_found_exception_when_an_attempt_to_get_an_unlocatable_service_is_made(
        ServiceLocatorInterface $serviceLocator
    ) {
        $serviceLocator->locate(Argument::type('string'), $this)->willThrow(NotFoundException::class);

        $this->shouldThrow(NotFoundException::class)->during('get', ['some.service']);
    }

    public function it_can_return_a_service_with_custom_id_using_the_class_name()
    {
        $service = new ContainerTestClass();

        $this->register($service, 'custom.id');

        $this->get(ContainerTestClass::class)->shouldReturn($service);
    }
}

class ContainerTestClass
{
}
