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

namespace spec\BenGorUser\User\Application\Service\ChangePassword;

use BenGorUser\User\Application\Service\ChangePassword\ByRequestRememberPasswordChangeUserPasswordSpecification;
use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordCommand;
use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordSpecification;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;
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
        ChangeUserPasswordCommand $command,
        UserRepository $repository
    ) {
        $command->rememberPasswordToken()->shouldBeCalled()->willReturn('non-exist-remember-password-token');
        $repository->userOfRememberPasswordToken(new UserToken('non-exist-remember-password-token'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringUser($command);
    }

    function it_returns_a_user(
        ChangeUserPasswordCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->rememberPasswordToken()->shouldBeCalled()->willReturn('remember-password-token');
        $repository->userOfRememberPasswordToken(
            new UserToken('remember-password-token')
        )->shouldBeCalled()->willReturn($user);

        $this->user($command)->shouldReturn($user);
    }
}
