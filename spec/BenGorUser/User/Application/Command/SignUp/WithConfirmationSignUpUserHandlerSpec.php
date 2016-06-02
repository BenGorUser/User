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

use BenGorUser\User\Application\Command\SignUp\WithConfirmationSignUpUserCommand;
use BenGorUser\User\Application\Command\SignUp\WithConfirmationSignUpUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactorySignUp;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of WithConfirmationSignUpUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class WithConfirmationSignUpUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserFactorySignUp $factory)
    {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encoded-password'),
            $factory
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(WithConfirmationSignUpUserHandler::class);
    }

    function it_signs_the_user_up(
        WithConfirmationSignUpUserCommand $command,
        UserRepository $repository,
        UserFactorySignUp $factory,
        User $user
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');
        $repository->userOfId($id)->shouldBeCalled()->willReturn(null);

        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');
        $repository->userOfEmail($email)->shouldBeCalled()->willReturn(null);

        $command->password()->shouldBeCalled()->willReturn('plain-password');

        $command->roles()->shouldBeCalled()->willReturn(['ROLE_USER']);
        $roles = [new UserRole('ROLE_USER')];

        $factory->build(
            $id, $email, Argument::type(UserPassword::class), $roles
        )->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_sign_up_if_user_id_already_exists(
        WithConfirmationSignUpUserCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');
        $repository->userOfId($id)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->during__invoke($command);
    }

    function it_does_not_sign_up_if_user_email_already_exists(
        WithConfirmationSignUpUserCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');
        $repository->userOfId($id)->shouldBeCalled()->willReturn(null);

        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');
        $repository->userOfEmail($email)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->during__invoke($command);
    }
}
