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

namespace spec\BenGorUser\User\Application\Command\LogIn;

use BenGorUser\User\Application\Command\LogIn\LogInUserCommand;
use BenGorUser\User\Application\Command\LogIn\LogInUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of LogInUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogInUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository, new DummyUserPasswordEncoder('encodedPassword'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LogInUserHandler::class);
    }

    function it_logs_the_user_in(
        LogInUserCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');

        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $command->password()->shouldBeCalled()->willReturn('plainPassword');

        $user->login('plainPassword', $encoder)->shouldBeCalled();

        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_log_if_user_does_not_exist(UserRepository $repository, LogInUserCommand $command)
    {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
