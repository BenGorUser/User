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

namespace spec\BenGorUser\User\Application\Command\LogOut;

use BenGorUser\User\Application\Command\LogOut\LogOutUserCommand;
use BenGorUser\User\Application\Command\LogOut\LogOutUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of LogOutUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogOutUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LogOutUserHandler::class);
    }

    function it_logs_the_user_out(UserRepository $repository, User $user, LogOutUserCommand $command)
    {
        $command->id()->shouldBeCalled()->willReturn('user-id');

        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);

        $user->logout()->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_logout_unknown_user(UserRepository $repository, LogOutUserCommand $command)
    {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
