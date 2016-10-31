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

namespace spec\BenGorUser\User\Application\Command\Invite;

use BenGorUser\User\Application\Command\Invite\ResendInvitationUserCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of ResendInvitationUserCommand class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ResendInvitationUserCommandSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('email@email.com');
        $this->shouldHaveType(ResendInvitationUserCommand::class);

        $this->email()->shouldReturn('email@email.com');
    }
}
