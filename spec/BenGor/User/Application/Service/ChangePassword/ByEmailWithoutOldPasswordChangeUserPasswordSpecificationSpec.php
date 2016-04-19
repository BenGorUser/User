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

use BenGor\User\Application\Service\ChangePassword\ByEmailWithoutOldPasswordChangeUserPasswordSpecification;
use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordRequest;
use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordSpecification;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of ByEmailWithoutOldPasswordChangeUserPasswordSpecification class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ByEmailWithoutOldPasswordChangeUserPasswordSpecificationSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ByEmailWithoutOldPasswordChangeUserPasswordSpecification::class);
    }

    function it_implements_specification_change_user_password()
    {
        $this->shouldImplement(ChangeUserPasswordSpecification::class);
    }

    function it_does_not_return_user_because_user_does_not_exist(
        ChangeUserPasswordRequest $request,
        UserRepository $repository
    ) {
        $request->email()->shouldBeCalled()->willReturn('non-exist@user-email.com');
        $repository->userOfEmail(new UserEmail('non-exist@user-email.com'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringUser($request);
    }

    function it_returns_a_user(
        ChangeUserPasswordRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $request->email()->shouldBeCalled()->willReturn('bengor@user.com');
        $repository->userOfEmail(new UserEmail('bengor@user.com'))
            ->shouldBeCalled()->willReturn($user);

        $this->user($request)->shouldReturn($user);
    }
}
