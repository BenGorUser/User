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

namespace spec\BenGorUser\User\Domain\Model\Event;

use BenGorUser\User\Domain\Model\Event\UserInvited;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserInvited class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserInvitedSpec extends ObjectBehavior
{
    function it_creates_event()
    {
        $id = new UserId('user-id');
        $email = new UserEmail('bengor@user.com');
        $invitationToken = new UserToken('invitation-token');
        $this->beConstructedWith($id, $email, $invitationToken);
        $this->shouldHaveType(UserInvited::class);

        $this->id()->shouldReturn($id);
        $this->email()->shouldReturn($email);
        $this->invitationToken()->shouldReturn($invitationToken);
        $this->occurredOn()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
    }
}
