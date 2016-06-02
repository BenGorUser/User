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

namespace spec\BenGorUser\User\Application\Command\GrantRole;

use BenGorUser\User\Application\Command\GrantRole\GrantUserRoleCommand;
use BenGorUser\User\Application\Command\GrantRole\GrantUserRoleHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of grant user role Command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GrantUserRoleHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GrantUserRoleHandler::class);
    }

    function it_grants_the_user_role(UserRepository $repository, User $user, GrantUserRoleCommand $command)
    {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $command->role()->shouldBeCalled()->willReturn('ROLE_USER');
        $id = new UserId('user-id');
        $role = new UserRole('ROLE_USER');

        $repository->userOfId($id)->shouldBeCalled()->willReturn($user);

        $user->grant($role)->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_grant_the_user_role_because_the_user_does_not_exist(
        UserRepository $repository,
        GrantUserRoleCommand $command
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');

        $repository->userOfId($id)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new UserDoesNotExistException())->during__invoke($command);
    }
}
