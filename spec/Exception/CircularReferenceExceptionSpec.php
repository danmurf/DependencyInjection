<?php

namespace spec\danmurf\DependencyInjection\Exception;

use danmurf\DependencyInjection\Exception\CircularReferenceException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CircularReferenceExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CircularReferenceException::class);
    }
}
