<?php

namespace spec\BenGor\User\Domain\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Domain\Model\User');
    }
}
