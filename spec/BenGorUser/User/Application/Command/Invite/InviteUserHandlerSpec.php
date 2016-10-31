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

use BenGorUser\User\Application\Command\Invite\InviteUserCommand;
use BenGorUser\User\Application\Command\Invite\InviteUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactoryInvite;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of InviteUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InviteUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $userRepository, UserFactoryInvite $factory)
    {
        $this->beConstructedWith($userRepository, $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InviteUserHandler::class);
    }

    function it_invites_user(
        InviteUserCommand $command,
        UserRepository $userRepository,
        UserFactoryInvite $factory,
        User $user
    ) {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');

        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn(null);

        $command->roles()->shouldBeCalled()->willReturn(['ROLE_USER']);
        $roles = [new UserRole('ROLE_USER')];
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');
        $factory->build($id, $email, $roles)->shouldBeCalled()->willReturn($user);

        $userRepository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_invite_when_user_already_exists(
        UserRepository $userRepository,
        User $user,
        InviteUserCommand $command
    ) {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');
        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->during__invoke($command);
    }
}
