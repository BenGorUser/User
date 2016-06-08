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

use BenGorUser\User\Domain\Model\Event\UserRememberPasswordRequested;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserRememberPasswordRequested class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserRememberPasswordRequestedSpec extends ObjectBehavior
{
    function it_creates_event()
    {
        $id = new UserId('user-id');
        $email = new UserEmail('bengor@user.com');
        $rememberPasswordToken = new UserToken('remember-password-token');
        $this->beConstructedWith($id, $email, $rememberPasswordToken);
        $this->shouldHaveType(UserRememberPasswordRequested::class);

        $this->id()->shouldReturn($id);
        $this->email()->shouldReturn($email);
        $this->rememberPasswordToken()->shouldReturn($rememberPasswordToken);
        $this->occurredOn()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
    }
}
