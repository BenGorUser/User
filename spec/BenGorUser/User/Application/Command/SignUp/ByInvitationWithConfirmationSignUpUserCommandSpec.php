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

namespace spec\BenGorUser\User\Application\Command\SignUp;

use BenGorUser\User\Application\Command\SignUp\ByInvitationWithConfirmationSignUpUserCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of ByInvitationWithConfirmationSignUpUserCommand class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationWithConfirmationSignUpUserCommandSpec extends ObjectBehavior
{
    function it_creates_a_command()
    {
        $this->beConstructedWith('invitation-token', 'plainPassword');
        $this->shouldHaveType(ByInvitationWithConfirmationSignUpUserCommand::class);

        $this->invitationToken()->shouldReturn('invitation-token');
        $this->password()->shouldReturn('plainPassword');
    }
}
