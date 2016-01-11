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

use BenGor\User\Application\Service\InviteUserRequest;
use BenGor\User\Application\Service\InviteUserService;
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
use Prophecy\Argument;

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
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserGuestFactory $factory,
        UserGuest $userGuest
    ) {
        $request = new InviteUserRequest('user@user.com');
        $id = new UserGuestId('id');

        $userRepository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail(Argument::type(UserEmail::class))
            ->shouldBeCalled()->willReturn(null);

        $userGuestRepository->nextIdentity()->shouldBeCalled()->willReturn($id);
        $factory->register($id, Argument::type(UserEmail::class))->shouldBeCalled()->willReturn($userGuest);

        $userGuestRepository->persist($userGuest)->shouldBeCalled();

        $this->execute($request);
    }

    function it_reinvites_user(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserGuest $userGuest
    ) {
        $request = new InviteUserRequest('user@user.com');

        $userRepository->userOfEmail(Argument::type(UserEmail::class))
            ->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail(Argument::type(UserEmail::class))
            ->shouldBeCalled()->willReturn($userGuest);
        $userGuest->regenerateInvitationToken()->shouldBeCalled();

        $userGuestRepository->persist($userGuest)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_invite_if_user_already_exist(UserRepository $userRepository, User $user)
    {
        $request = new InviteUserRequest('user@user.com');

        $userRepository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->duringExecute($request);
    }
}
