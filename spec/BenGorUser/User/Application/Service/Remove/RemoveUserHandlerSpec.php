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

namespace spec\BenGorUser\User\Application\Service\Remove;

use BenGorUser\User\Application\Service\Remove\RemoveUserCommand;
use BenGorUser\User\Application\Service\Remove\RemoveUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of RemoveUserService class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RemoveUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveUserHandler::class);
    }

    function it_does_not_remove_because_user_does_no_exist(RemoveUserCommand $command, UserRepository $repository)
    {
        $command->id()->shouldBeCalled()->willReturn('non-exist-user-id');
        $repository->userOfId(new UserId('non-exist-user-id'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }

    function it_removes_user(RemoveUserCommand $command, UserRepository $repository, User $user)
    {
        $command->id()->shouldBeCalled()->willReturn('user-id');

        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);
        $repository->remove($user)->shouldBeCalled();

        $this->__invoke($command);
    }
}
