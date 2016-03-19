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

use BenGor\User\Application\Service\SignUpAndEnableUserByInvitationRequest;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of sign up user and enable by invitation request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SignUpAndEnableUserByInvitationRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('dummy-invitation-token', 'plainPassword', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->shouldHaveType(SignUpAndEnableUserByInvitationRequest::class);

        $this->invitationToken()->shouldBe('dummy-invitation-token');
        $this->password()->shouldBe('plainPassword');
        $this->roles()->shouldReturn(['ROLE_USER', 'ROLE_ADMIN']);
    }
}
