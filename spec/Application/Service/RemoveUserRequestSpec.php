<?php

/*
 * This file is part of the BenGorUser library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenGor\User\Application\Service;

use BenGor\User\Application\Service\RemoveUserRequest;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of RemoveUserRequest class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RemoveUserRequestSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('a-plain-string-id', 'a-plain-user-password');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveUserRequest::class);
    }

    function it_should_have_an_id()
    {
        $this->id()->shouldReturn('a-plain-string-id');
    }

    function it_should_have_a_password()
    {
        $this->password()->shouldReturn('a-plain-user-password');
    }
}
