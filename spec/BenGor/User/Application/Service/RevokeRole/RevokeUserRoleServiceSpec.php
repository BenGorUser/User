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

namespace spec\BenGor\User\Application\Service\RevokeRole;

use BenGor\User\Application\Service\RevokeRole\RevokeUserRoleRequest;
use BenGor\User\Application\Service\RevokeRole\RevokeUserRoleService;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of revoke user role service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RevokeUserRoleServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RevokeUserRoleService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_revokes_the_user_role(
        RevokeUserRoleRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $request->id()->shouldBeCalled()->willReturn('user-id');
        $request->role()->shouldBeCalled()->willReturn('ROLE_USER');
        $id = new UserId('user-id');
        $role = new UserRole('ROLE_USER');

        $repository->userOfId($id)->shouldBeCalled()->willReturn($user);

        $user->revoke($role)->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_revoke_the_user_role_because_the_user_does_not_exist(
        RevokeUserRoleRequest $request,
        UserRepository $repository
    ) {
        $request->id()->shouldBeCalled()->willReturn('user-id');
        $request->role()->shouldBeCalled()->willReturn('ROLE_USER');
        $id = new UserId('user-id');

        $repository->userOfId($id)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringExecute($request);
    }
}
