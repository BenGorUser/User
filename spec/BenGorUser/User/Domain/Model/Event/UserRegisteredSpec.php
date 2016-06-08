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

use BenGorUser\User\Domain\Model\Event\UserRegistered;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserRegistered class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserRegisteredSpec extends ObjectBehavior
{
    function it_creates_event()
    {
        $id = new UserId('user-id');
        $email = new UserEmail('bengor@user.com');
        $confirmationToken = new UserToken('confirmation-token');
        $this->beConstructedWith($id, $email, $confirmationToken);
        $this->shouldHaveType(UserRegistered::class);

        $this->id()->shouldReturn($id);
        $this->email()->shouldReturn($email);
        $this->confirmationToken()->shouldReturn($confirmationToken);
        $this->occurredOn()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
    }
}
