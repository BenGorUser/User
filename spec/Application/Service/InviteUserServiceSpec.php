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
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserGuest;
use BenGor\User\Domain\Model\UserGuestId;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of invite user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InviteUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $userRepository, UserGuestRepository $userGuestRepository)
    {
        $this->beConstructedWith($userRepository, $userGuestRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Application\Service\InviteUserService');
    }

    function it_implements_application_service()
    {
        $this->shouldImplement('Ddd\Application\Service\ApplicationService');
    }

    function it_invites_user(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository
    ) {
        $request = new InviteUserRequest('user@user.com');
        $id = new UserGuestId('id');

        $userRepository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn(null);

        $userGuestRepository->nextIdentity()->shouldBeCalled()->willReturn($id);
        $userGuestRepository->persist(Argument::type('BenGor\User\Domain\Model\UserGuest'))->shouldBeCalled();

        $this->execute($request);
    }

    function it_reinvites_user(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserGuest $userGuest
    ) {
        $request = new InviteUserRequest('user@user.com');

        $userRepository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn(null);
        $userGuestRepository->userGuestOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn($userGuest);
        $userGuest->regenerateInvitationToken()->shouldBeCalled();

        $userGuestRepository->persist($userGuest)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_invite_if_user_already_exist(UserRepository $userRepository, User $user)
    {
        $request = new InviteUserRequest('user@user.com');

        $userRepository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn($user);

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserAlreadyExistException')->duringExecute($request);
    }
}
