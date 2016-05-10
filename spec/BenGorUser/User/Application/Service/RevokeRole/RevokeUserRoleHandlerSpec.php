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

namespace spec\BenGorUser\User\Application\Service\RevokeRole;

use BenGorUser\User\Application\Service\RevokeRole\RevokeUserRoleCommand;
use BenGorUser\User\Application\Service\RevokeRole\RevokeUserRoleHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of revoke user role service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RevokeUserRoleHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RevokeUserRoleHandler::class);
    }

    function it_revokes_the_user_role(
        RevokeUserRoleCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $command->role()->shouldBeCalled()->willReturn('ROLE_USER');
        $id = new UserId('user-id');
        $role = new UserRole('ROLE_USER');

        $repository->userOfId($id)->shouldBeCalled()->willReturn($user);

        $user->revoke($role)->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_revoke_the_user_role_because_the_user_does_not_exist(
        RevokeUserRoleCommand $command,
        UserRepository $repository
    ) {
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $id = new UserId('user-id');

        $repository->userOfId($id)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
