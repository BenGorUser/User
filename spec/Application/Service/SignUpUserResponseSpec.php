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

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of sign up user response class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SignUpUserResponseSpec extends ObjectBehavior
{
    function it_creates_response()
    {
        $user = new User(
            new UserId(),
            new UserEmail('user@user.com'),
            UserPassword::fromEncoded('123456', 'dummy-salt'),
            [new UserRole('ROLE_USER')]
        );

        $this->beConstructedWith($user);
        $this->shouldHaveType('BenGor\User\Application\Service\SignUpUserResponse');

        $this->user()->shouldBe($user);
    }
}
