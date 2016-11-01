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

namespace spec\BenGorUser\User\Domain\Model;

use BenGorUser\User\Domain\Model\Exception\UserInactiveException;
use BenGorUser\User\Domain\Model\Exception\UserInvitationAlreadyAcceptedException;
use BenGorUser\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGorUser\User\Domain\Model\Exception\UserRoleAlreadyGrantedException;
use BenGorUser\User\Domain\Model\Exception\UserRoleAlreadyRevokedException;
use BenGorUser\User\Domain\Model\Exception\UserRoleInvalidException;
use BenGorUser\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of User class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserSpec extends ObjectBehavior
{
    function let()
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');

        $this->beConstructedSignUp(
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
        $this->email()->email()->shouldReturn('test@test.com');
        $this->confirmationToken()->token()->shouldNotBe(null);
        $this->isEnabled()->shouldReturn(false);
        $this->createdOn()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
        $this->updatedOn()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);

        $this->events()->shouldHaveCount(2);
        $this->eraseEvents();
        $this->events()->shouldHaveCount(0);
    }

    function it_enables_an_account()
    {
        $this->isEnabled()->shouldReturn(false);
        $this->confirmationToken()->shouldReturnAnInstanceOf(UserToken::class);
        $this->enableAccount();
        $this->isEnabled()->shouldReturn(true);
        $this->confirmationToken()->shouldReturn(null);
    }

    function it_logs_in_user()
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $this->enableAccount();

        $this->lastLogin()->shouldReturn(null);

        $this->login('plainPassword', $encoder);

        $this->lastLogin()->shouldReturnAnInstanceOf(DateTimeImmutable::class);
    }

    function it_does_not_log_if_user_not_enabled()
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');

        $this->shouldThrow(UserInactiveException::class)->duringLogin('plainPassword', $encoder);
    }

    function it_does_not_log_if_user_invalid_password()
    {
        $encoder = new DummyUserPasswordEncoder('otherEncodedPassword', false);
        $this->enableAccount();

        $this->shouldThrow(UserPasswordInvalidException::class)->duringLogin('plainPassword', $encoder);
    }

    function it_does_not_log_out_if_user_not_enabled()
    {
        $this->shouldThrow(UserInactiveException::class)->duringLogout();
    }

    function it_log_out()
    {
        $this->enableAccount();
        $this->logout();
    }

    function it_accepts_invitation_request()
    {
        $this->beConstructedInvite(
            new UserId(),
            new UserEmail('test@test.com'),
            [new UserRole('ROLE_USER')]
        );

        $this->invitationToken()->shouldReturnAnInstanceOf(UserToken::class);

        $this->acceptInvitation();

        $this->invitationToken()->shouldReturn(null);
    }

    function it_does_not_accept_invitation_request_when_the_invitation_is_already_accepted()
    {
        $this->isInvitationTokenAccepted()->shouldReturn(true);
        $this->shouldThrow(UserInvitationAlreadyAcceptedException::class)->duringAcceptInvitation();
    }

    function it_regenerates_when_token_is_null()
    {
        $this->shouldThrow(
            UserInvitationAlreadyAcceptedException::class
        )->duringRegenerateInvitationToken();
    }

    function it_regenerates_invitation_token()
    {
        $this->beConstructedInvite(
            new UserId(),
            new UserEmail('test@test.com'),
            [new UserRole('ROLE_USER')]
        );

        $token = $this->invitationToken();
        $this->regenerateInvitationToken();
        $this->invitationToken()->shouldNotBe($token);
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
        $this->changePassword($newPassword);
        $this->rememberPasswordToken()->shouldReturn(null);
        $this->password()->shouldReturn($newPassword);
    }

    function it_manages_roles()
    {
        $role = new UserRole('ROLE_USER');
        $roleAdmin = new UserRole('ROLE_ADMIN');
        $notAvailableRole = new UserRole('NOT_AVAILABLE_ROLE');

        $this->isRoleAllowed($role)->shouldReturn(true);
        $this->shouldThrow(new UserRoleAlreadyGrantedException())->duringGrant($role);
        $this->roles()->shouldHaveCount(1);
        $this->grant($roleAdmin);
        $this->roles()->shouldHaveCount(2);
        $this->revoke($role);
        $this->roles()->shouldHaveCount(1);
        $this->shouldThrow(new UserRoleAlreadyRevokedException())->duringRevoke($role);
        $this->shouldThrow(new UserRoleInvalidException())->duringRevoke($notAvailableRole);
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

    function it_manages_remember_password_token_expirations()
    {
        $this->shouldThrow(UserTokenNotFoundException::class)->duringIsRememberPasswordTokenExpired();
        $this->rememberPassword();
        $this->isRememberPasswordTokenExpired()->shouldReturn(false);
    }

    function it_manages_invitation_token_expirations()
    {
        $this->beConstructedInvite(
            new UserId(),
            new UserEmail('test@test.com'),
            [new UserRole('ROLE_USER')]
        );

        $this->isInvitationTokenExpired()->shouldReturn(false);
        $this->isInvitationTokenAccepted()->shouldReturn(false);
        $this->acceptInvitation();
        $this->isInvitationTokenAccepted()->shouldReturn(true);
    }
}
