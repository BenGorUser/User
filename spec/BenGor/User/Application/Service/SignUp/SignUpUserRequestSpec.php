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

namespace spec\BenGor\User\Application\Service\SignUp;

use BenGor\User\Application\Service\SignUp\SignUpUserRequest;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of sign up user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SignUpUserRequestSpec extends ObjectBehavior
{
    function it_creates_request_from_email()
    {
        $this->beConstructedFromEmail('user@user.net', 'plainPassword', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->shouldHaveType(SignUpUserRequest::class);

        $this->email()->shouldReturn('user@user.net');
        $this->password()->shouldReturn('plainPassword');
        $this->roles()->shouldReturn(['ROLE_USER', 'ROLE_ADMIN']);
        $this->invitationToken()->shouldReturn(null);
    }

    function it_creates_request_from_invitation_token()
    {
        $this->beConstructedFromInvitationToken('invitation-token', 'plainPassword', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->shouldHaveType(SignUpUserRequest::class);

        $this->invitationToken()->shouldReturn('invitation-token');
        $this->password()->shouldReturn('plainPassword');
        $this->roles()->shouldReturn(['ROLE_USER', 'ROLE_ADMIN']);
        $this->email()->shouldReturn(null);
    }
}
