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
}
