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
use BenGorUser\User\Domain\Model\UserFactorySignUp as BaseUserFactorySignUp;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Infrastructure\Domain\Model\UserFactorySignUp;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserFactorySignUp class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserFactorySignUpSpec extends ObjectBehavior
{
    function it_builds()
    {
        $this->beConstructedWith(User::class);
        $this->shouldHaveType(UserFactorySignUp::class);
        $this->shouldImplement(BaseUserFactorySignUp::class);

        $this->build(
            new UserId('user-id'),
            new UserEmail('bengor@user.com'),
            UserPassword::fromEncoded('encoded-pass', null),
            [new UserRole('ROLE_USER')]
        )->shouldReturnAnInstanceOf(User::class);
    }
}
