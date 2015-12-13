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

use BenGor\User\Application\Service\SignUpUserByInvitationRequest;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserFactory;
use BenGor\User\Domain\Model\UserGuest;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use BenGor\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of sign up user by invitation service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SignUpUserByInvitationServiceSpec extends ObjectBehavior
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
        $this->shouldHaveType('BenGor\User\Application\Service\SignUpUserByInvitationService');
    }

    function it_implements_application_service()
    {
        $this->shouldImplement('Ddd\Application\Service\ApplicationService');
    }

    function it_signs_the_user_up_by_invitation(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        User $user,
        UserGuest $userGuest,
        UserFactory $factory
    ) {
        $request = new SignUpUserByInvitationRequest('dummy-invitation-token', 'plainPassword', ['ROLE_ADMIN']);
        $token = new UserToken('dummy-invitation-token');
        $email = new UserEmail('mail@mail.com');
        $id = new UserId('id');
        $roles = [new UserRole('ROLE_ADMIN')];

        $userGuestRepository->userGuestOfInvitationToken($token)->shouldBeCalled()->willReturn($userGuest);
        $userRepository->userOfEmail(
            Argument::type('BenGor\User\Domain\Model\UserEmail')
        )->shouldBeCalled()->willReturn(null);

        $userGuest->email()->shouldBeCalled()->willReturn($email);
        $userRepository->nextIdentity()->shouldBeCalled()->willReturn($id);
        $factory->register(
            $id, $email, Argument::type('BenGor\User\Domain\Model\UserPassword'), $roles
        )->shouldBeCalled()->willReturn($user);
        $userRepository->persist($user)->shouldBeCalled();
        $userGuestRepository->remove($userGuest)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_sign_up_by_invitation_if_user_guest_does_not_exist(UserGuestRepository $userGuestRepository)
    {
        $request = new SignUpUserByInvitationRequest('dummy-invitation-token', 'plainPassword', ['ROLE_USER']);
        $token = new UserToken('dummy-invitation-token');

        $userGuestRepository->userGuestOfInvitationToken($token)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserGuestDoesNotExistException')->duringExecute($request);
    }

    function it_does_not_sign_up_if_user_exists(
        UserRepository $userRepository,
        UserGuestRepository $userGuestRepository,
        User $user,
        UserGuest $userGuest
    ) {
        $request = new SignUpUserByInvitationRequest('dummy-invitation-token', 'plainPassword', ['ROLE_USER']);
        $token = new UserToken('dummy-invitation-token');
        $email = new UserEmail('mail@mail.com');

        $userGuestRepository->userGuestOfInvitationToken($token)->shouldBeCalled()->willReturn($userGuest);
        $userGuest->email()->shouldBeCalled()->willReturn($email);
        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserAlreadyExistException')->duringExecute($request);
    }
}
