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

namespace spec\BenGor\User\Application\Service\ChangePassword;

use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordRequest;
use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordSpecification;
use BenGor\User\Application\Service\ChangePassword\DefaultChangeUserPasswordSpecification;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of default change user password specification class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DefaultChangeUserPasswordSpecificationSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserPasswordEncoder $encoder)
    {
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DefaultChangeUserPasswordSpecification::class);
    }

    function it_implements_specification_change_user_password()
    {
        $this->shouldImplement(ChangeUserPasswordSpecification::class);
    }

    function it_does_not_return_user_because_user_does_not_exist(
        ChangeUserPasswordRequest $request,
        UserRepository $repository
    ) {
        $request->id()->shouldBeCalled()->willReturn('non-exist-user-id');
        $repository->userOfId(new UserId('non-exist-user-id'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringUser($request);
    }

    function it_does_not_return_user_because_user_password_is_invalid(
        ChangeUserPasswordRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $encoder = new DummyUserPasswordEncoder('encoded-pass', false);
        $userPassword = UserPassword::fromPlain('plain-pass', $encoder, 'dummy-salt');

        $this->beConstructedWith($repository, $encoder);

        $request->id()->shouldBeCalled()->willReturn('user-id');
        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);
        $user->password()->shouldBeCalled()->willReturn($userPassword);
        $request->oldPlainPassword()->shouldBeCalled()->willReturn('old-plain-pass');

        $this->shouldThrow(UserPasswordInvalidException::class)->duringUser($request);
    }

    function it_returns_a_user(
        ChangeUserPasswordRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $encoder = new DummyUserPasswordEncoder('encoded-pass');
        $userPassword = UserPassword::fromPlain('old-plain-pass', $encoder, 'dummy-salt');

        $request->id()->shouldBeCalled()->willReturn('user-id');
        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);
        $user->password()->shouldBeCalled()->willReturn($userPassword);
        $request->oldPlainPassword()->shouldBeCalled()->willReturn('old-plain-pass');

        $this->user($request)->shouldReturn($user);
    }
}
