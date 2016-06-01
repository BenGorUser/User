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

namespace spec\BenGorUser\User\Application\Command\SignUp;

use BenGorUser\User\Application\Command\SignUp\ByInvitationSignUpUserCommand;
use BenGorUser\User\Application\Command\SignUp\ByInvitationSignUpUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGorUser\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactory;
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of ByInvitationSignUpUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationSignUpUserHandlerSpec extends ObjectBehavior
{
    function let(
        UserRepository $repository,
        UserFactory $factory,
        UserGuestRepository $userGuestRepository
    ) {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encoded-password'),
            $factory,
            $userGuestRepository
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ByInvitationSignUpUserHandler::class);
    }

    function it_signs_the_user_up(
        ByInvitationSignUpUserCommand $command,
        UserRepository $repository,
        UserFactory $factory,
        User $user,
        UserGuestRepository $userGuestRepository,
        UserGuest $userGuest
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');
        $repository->userOfId($id)->shouldBeCalled()->willReturn(null);

        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $userGuestRepository->userGuestOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn($userGuest);

        $email = new UserEmail('user@user.com');
        $userGuest->email()->shouldBeCalled()->willReturn($email);
        $userGuestRepository->remove($userGuest)->shouldBeCalled();

        $command->password()->shouldBeCalled()->willReturn('plain-password');

        $command->roles()->shouldBeCalled()->willReturn(['ROLE_USER']);
        $roles = [new UserRole('ROLE_USER')];

        $factory->register(
            $id, $email, Argument::type(UserPassword::class), $roles
        )->shouldBeCalled()->willReturn($user);
        $user->enableAccount()->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_sign_up_if_user_id_already_exists(
        ByInvitationSignUpUserCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');
        $repository->userOfId($id)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->during__invoke($command);
    }

    function it_does_not_sign_up_because_user_guest_does_not_exist(
        ByInvitationSignUpUserCommand $command,
        UserRepository $repository,
        UserGuestRepository $userGuestRepository
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');
        $repository->userOfId($id)->shouldBeCalled()->willReturn(null);

        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $userGuestRepository->userGuestOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserGuestDoesNotExistException::class)->during__invoke($command);
    }
}
