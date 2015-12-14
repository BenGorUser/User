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

namespace spec\BenGor\User\Domain\Model;

use BenGor\User\Domain\Model\Exception\UserRoleAlreadyGrantedException;
use BenGor\User\Domain\Model\Exception\UserRoleAlreadyRevokedException;
use BenGor\User\Domain\Model\Exception\UserRoleInvalidException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRole;
use BenGor\User\Domain\Model\UserToken;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of User domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserSpec extends ObjectBehavior
{
    function let()
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');

        $this->beConstructedWith(
            new UserId(),
            new UserEmail('test@test.com'),
            UserPassword::fromPlain('strongpassword', $encoder),
            [new UserRole('ROLE_USER')]
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function it_registers_a_user()
    {
        $this->id()->id()->shouldNotBe(null);
        $this->email()->email()->shouldBe('test@test.com');
        $this->confirmationToken()->token()->shouldNotBe(null);
        $this->isEnabled()->shouldBe(false);
    }

    function it_enables_an_account()
    {
        $this->isEnabled()->shouldBe(false);
        $this->confirmationToken()->shouldReturnAnInstanceOf(UserToken::class);
        $this->enableAccount();
        $this->isEnabled()->shouldBe(true);
        $this->confirmationToken()->shouldReturn(null);
    }

    function it_logs_in_user()
    {
        $this->lastLogin()->shouldReturn(null);
        $this->login();
        $this->lastLogin()->shouldReturnAnInstanceOf(DateTimeImmutable::class);
    }

    function it_remembers_password()
    {
        $this->rememberPasswordToken()->shouldReturn(null);
        $this->rememberPassword();
        $this->rememberPasswordToken()->shouldReturnAnInstanceOf(UserToken::class);
    }

    function it_changes_password()
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $newPassword = UserPassword::fromPlain('strongnewpassword', $encoder);

        $this->rememberPassword();
        $this->rememberPasswordToken()->shouldReturnAnInstanceOf(UserToken::class);
        $this->changePassword($this->password(), $newPassword);
        $this->rememberPasswordToken()->shouldReturn(null);
        $this->password()->shouldReturn($newPassword);
    }

    function it_manages_roles()
    {
        $role = new UserRole('ROLE_USER');
        $roleAdmin = new UserRole('ROLE_ADMIN');

        $this->isRoleAllowed($role)->shouldReturn(true);
        $this->shouldThrow(new UserRoleAlreadyGrantedException())->duringGrant($role);
        $this->roles()->shouldHaveCount(1);
        $this->grant($roleAdmin);
        $this->roles()->shouldHaveCount(2);
        $this->revoke($role);
        $this->roles()->shouldHaveCount(1);
        $this->shouldThrow(new UserRoleAlreadyRevokedException())->duringRevoke($role);
        $this->roles()->shouldHaveCount(1);
    }

    function it_does_not_grant_role_because_the_role_is_not_a_user_role_instance()
    {
        $this->isRoleAllowed(new UserRole('ROLE_NOT_AVAILABLE'))->shouldReturn(false);
        $this->shouldThrow(new UserRoleInvalidException())->duringGrant(new UserRole('ROLE_NOT_AVAILABLE'));
    }

    function it_does_not_grant_role_because_the_role_is_already_granted()
    {
        $role = new UserRole('ROLE_USER');

        $this->isRoleAllowed($role)->shouldReturn(true);
        $this->isGranted($role)->shouldReturn(true);
        $this->shouldThrow(new UserRoleAlreadyGrantedException())->duringGrant($role);
    }
}
