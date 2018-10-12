<?php

namespace spec\danmurf\DependencyInjection\Exception;

use danmurf\DependencyInjection\Exception\NotFoundException;
use PhpSpec\ObjectBehavior;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NotFoundException::class);
    }

    public function it_implements_not_found_exception_interface()
    {
        $this->shouldImplement(NotFoundExceptionInterface::class);
    }
}
