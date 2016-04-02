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

use BenGor\User\Application\Service\ChangePassword\ByRequestRememberPasswordChangeUserPasswordSpecification;
use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordRequest;
use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordSpecification;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of by request remember password change user password specification class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ByRequestRememberPasswordChangeUserPasswordSpecificationSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ByRequestRememberPasswordChangeUserPasswordSpecification::class);
    }

    function it_implements_specification_change_user_password()
    {
        $this->shouldImplement(ChangeUserPasswordSpecification::class);
    }

    function it_does_not_return_user_because_user_does_not_exist(
        ChangeUserPasswordRequest $request,
        UserRepository $repository
    ) {
        $request->rememberPasswordToken()->shouldBeCalled()->willReturn('non-exist-remember-password-token');
        $repository->userOfRememberPasswordToken(new UserToken('non-exist-remember-password-token'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringUser($request);
    }

    function it_returns_a_user(
        ChangeUserPasswordRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $request->rememberPasswordToken()->shouldBeCalled()->willReturn('remember-password-token');
        $repository->userOfRememberPasswordToken(
            new UserToken('remember-password-token')
        )->shouldBeCalled()->willReturn($user);

        $this->user($request)->shouldReturn($user);
    }
}
