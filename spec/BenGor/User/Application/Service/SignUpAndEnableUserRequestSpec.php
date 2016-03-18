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

use BenGor\User\Application\Service\SignUpAndEnableUserRequest;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of sign up user and enable request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SignUpAndEnableUserRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('user@user.net', 'plainPassword', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->shouldHaveType(SignUpAndEnableUserRequest::class);

        $this->email()->shouldReturn('user@user.net');
        $this->password()->shouldReturn('plainPassword');
        $this->roles()->shouldReturn(['ROLE_USER', 'ROLE_ADMIN']);
    }
}
