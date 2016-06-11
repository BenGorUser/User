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

namespace spec\BenGorUser\User\Infrastructure\Domain\Model;

use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactoryInvite as BaseUserFactoryInvite;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Infrastructure\Domain\Model\UserFactoryInvite;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserFactoryInvite class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserFactoryInviteSpec extends ObjectBehavior
{
    function it_builds()
    {
        $this->beConstructedWith(User::class);
        $this->shouldHaveType(UserFactoryInvite::class);
        $this->shouldImplement(BaseUserFactoryInvite::class);

        $this->build(
            new UserId('user-id'),
            new UserEmail('bengor@user.com'),
            [new UserRole('ROLE_USER')]
        )->shouldReturnAnInstanceOf(User::class);
    }
}
