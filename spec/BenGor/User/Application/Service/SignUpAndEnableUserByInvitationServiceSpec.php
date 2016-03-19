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

use BenGor\User\Application\Service\SignUpAndEnableUserByInvitationRequest;
use BenGor\User\Application\Service\SignUpAndEnableUserByInvitationResponse;
use BenGor\User\Application\Service\SignUpAndEnableUserByInvitationService;
use BenGor\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGor\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserFactory;
use BenGor\User\Domain\Model\UserGuest;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use BenGor\User\Domain\Model\UserToken;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of sign up and enable user by invitation service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SignUpAndEnableUserByInvitationServiceSpec extends ObjectBehavior
{
    function let(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        UserPasswordEncoder $encoder,
        UserFactory $factory
    ) {
        $this->beConstructedWith($userRepository, $userGuestRepository, $encoder, $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SignUpAndEnableUserByInvitationService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_signs_the_user_up_by_invitation(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        User $user,
        UserGuest $userGuest,
        UserFactory $factory
    ) {
        $request = new SignUpAndEnableUserByInvitationRequest(
            'dummy-invitation-token', 'plainPassword', ['ROLE_ADMIN']
        );
        $token = new UserToken('dummy-invitation-token');
        $email = new UserEmail('mail@mail.com');
        $id = new UserId('id');
        $roles = [new UserRole('ROLE_ADMIN')];

        $userGuestRepository->userGuestOfInvitationToken($token)->shouldBeCalled()->willReturn($userGuest);
        $userRepository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn(null);

        $userGuest->email()->shouldBeCalled()->willReturn($email);
        $userRepository->nextIdentity()->shouldBeCalled()->willReturn($id);
        $factory->register(
            $id, $email, Argument::type(UserPassword::class), $roles
        )->shouldBeCalled()->willReturn($user);
        $userRepository->persist($user)->shouldBeCalled();
        $user->enableAccount()->shouldBeCalled();
        $userGuestRepository->remove($userGuest)->shouldBeCalled();

        $this->execute($request)->shouldReturnAnInstanceOf(SignUpAndEnableUserByInvitationResponse::class);
    }

    function it_does_not_sign_up_by_invitation_if_user_guest_does_not_exist(UserGuestRepository $userGuestRepository)
    {
        $request = new SignUpAndEnableUserByInvitationRequest(
            'dummy-invitation-token', 'plainPassword', ['ROLE_USER']
        );
        $token = new UserToken('dummy-invitation-token');

        $userGuestRepository->userGuestOfInvitationToken($token)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserGuestDoesNotExistException::class)->duringExecute($request);
    }

    function it_does_not_sign_up_if_user_exists(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        User $user,
        UserGuest $userGuest
    ) {
        $request = new SignUpAndEnableUserByInvitationRequest(
            'dummy-invitation-token', 'plainPassword', ['ROLE_USER']
        );
        $token = new UserToken('dummy-invitation-token');
        $email = new UserEmail('mail@mail.com');

        $userGuestRepository->userGuestOfInvitationToken($token)->shouldBeCalled()->willReturn($userGuest);
        $userGuest->email()->shouldBeCalled()->willReturn($email);
        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->duringExecute($request);
    }
}
