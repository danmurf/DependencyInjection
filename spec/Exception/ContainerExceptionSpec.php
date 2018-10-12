<?php

namespace spec\danmurf\DependencyInjection\Exception;

use danmurf\DependencyInjection\Exception\ContainerException;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerExceptionInterface;

class ContainerExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ContainerException::class);
    }

    public function it_implements_container_exception_interface()
    {
        $this->shouldImplement(ContainerExceptionInterface::class);
    }
}
