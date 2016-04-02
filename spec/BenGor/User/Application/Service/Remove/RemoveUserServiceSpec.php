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

namespace spec\BenGor\User\Application\Service\Remove;

use BenGor\User\Application\Service\Remove\RemoveUserRequest;
use BenGor\User\Application\Service\Remove\RemoveUserService;
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of RemoveUserService class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RemoveUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $encoder = new DummyUserPasswordEncoder('encoded-password');
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveUserService::class);
    }

    function it_does_not_remove_user_password_do_not_match(
        RemoveUserRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $encoder = new DummyUserPasswordEncoder('encoded-password', false);
        $this->beConstructedWith($repository, $encoder);

        $request->id()->shouldBeCalled()->willReturn('user-id');
        $request->password()->shouldBeCalled()->willReturn('plain-password');
        $password = UserPassword::fromPlain('wrongPassword', $encoder);

        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);
        $user->password()->shouldBeCalled()->willReturn($password);

        $this->shouldThrow(UserPasswordInvalidException::class)->duringExecute($request);
    }

    function it_removes_user(
        RemoveUserRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $encoder = new DummyUserPasswordEncoder('encoded-password');
        $request->id()->shouldBeCalled()->willReturn('user-id');
        $request->password()->shouldBeCalled()->willReturn('plain-password');
        $password = UserPassword::fromPlain('plain-password', $encoder);

        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);
        $user->password()->shouldBeCalled()->willReturn($password);
        $repository->remove($user)->shouldBeCalled();

        $this->execute($request);
    }
}
