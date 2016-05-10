<?php

/*
 * This file is part of the BenGorUser package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenGorUser\User\Application\Service\SignUp;

use BenGorUser\User\Application\Service\SignUp\SignUpUserCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of sign up user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SignUpUserCommandSpec extends ObjectBehavior
{
    function it_creates_request_from_email()
    {
        $this->beConstructedFromEmail('user@user.net', 'plainPassword', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->shouldHaveType(SignUpUserCommand::class);

        $this->email()->shouldReturn('user@user.net');
        $this->password()->shouldReturn('plainPassword');
        $this->roles()->shouldReturn(['ROLE_USER', 'ROLE_ADMIN']);
        $this->invitationToken()->shouldReturn(null);
    }

    function it_creates_request_from_invitation_token()
    {
        $this->beConstructedFromInvitationToken('invitation-token', 'plainPassword', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->shouldHaveType(SignUpUserCommand::class);

        $this->invitationToken()->shouldReturn('invitation-token');
        $this->password()->shouldReturn('plainPassword');
        $this->roles()->shouldReturn(['ROLE_USER', 'ROLE_ADMIN']);
        $this->email()->shouldReturn(null);
    }
}
