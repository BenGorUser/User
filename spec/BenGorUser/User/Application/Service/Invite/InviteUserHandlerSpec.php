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
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestFactory;
use BenGorUser\User\Domain\Model\UserGuestId;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of invite user Command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InviteUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $userRepository, UserGuestRepository $userGuestRepository, UserGuestFactory $factory)
    {
        $this->beConstructedWith($userRepository, $userGuestRepository, $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InviteUserHandler::class);
    }

    function it_invites_user(
        InviteUserCommand $command,
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserGuestFactory $factory,
        UserGuest $userGuest
    ) {
        $id = new UserGuestId('id');

        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');

        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail($email)
            ->shouldBeCalled()->willReturn(null);

        $userGuestRepository->nextIdentity()->shouldBeCalled()->willReturn($id);
        $factory->register($id, $email)->shouldBeCalled()->willReturn($userGuest);

        $userGuestRepository->persist($userGuest)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_reinvites_user(
        InviteUserCommand $command,
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserGuest $userGuest
    ) {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');

        $userRepository->userOfEmail($email)
            ->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail($email)
            ->shouldBeCalled()->willReturn($userGuest);
        $userGuest->regenerateInvitationToken()->shouldBeCalled();

        $userGuestRepository->persist($userGuest)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_invite_if_user_already_exist(
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
