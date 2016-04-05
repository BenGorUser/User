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

namespace spec\BenGor\User\Application\Service\Invite;

use BenGor\User\Application\Service\Invite\InviteUserRequest;
use BenGor\User\Application\Service\Invite\InviteUserService;
use BenGor\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserGuest;
use BenGor\User\Domain\Model\UserGuestFactory;
use BenGor\User\Domain\Model\UserGuestId;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserRepository;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of invite user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InviteUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $userRepository, UserGuestRepository $userGuestRepository, UserGuestFactory $factory)
    {
        $this->beConstructedWith($userRepository, $userGuestRepository, $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InviteUserService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_invites_user(
        InviteUserRequest $request,
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserGuestFactory $factory,
        UserGuest $userGuest
    ) {
        $id = new UserGuestId('id');

        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');

        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail($email)
            ->shouldBeCalled()->willReturn(null);

        $userGuestRepository->nextIdentity()->shouldBeCalled()->willReturn($id);
        $factory->register($id, $email)->shouldBeCalled()->willReturn($userGuest);

        $userGuestRepository->persist($userGuest)->shouldBeCalled();

        $this->execute($request);
    }

    function it_reinvites_user(
        InviteUserRequest $request,
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserGuest $userGuest
    ) {
        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');

        $userRepository->userOfEmail($email)
            ->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail($email)
            ->shouldBeCalled()->willReturn($userGuest);
        $userGuest->regenerateInvitationToken()->shouldBeCalled();

        $userGuestRepository->persist($userGuest)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_invite_if_user_already_exist(
        UserRepository $userRepository,
        User $user,
        InviteUserRequest $request
    ) {
        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');
        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->duringExecute($request);
    }
}
