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

use BenGorUser\User\Application\Service\ChangePassword\ByEmailWithoutOldPasswordChangeUserPasswordSpecification;
use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordCommand;
use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordSpecification;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserRepository;
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
        ChangeUserPasswordCommand $command,
        UserRepository $repository
    ) {
        $command->email()->shouldBeCalled()->willReturn('non-exist@user-email.com');
        $repository->userOfEmail(new UserEmail('non-exist@user-email.com'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringUser($command);
    }

    function it_returns_a_user(
        ChangeUserPasswordCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->email()->shouldBeCalled()->willReturn('bengor@user.com');
        $repository->userOfEmail(new UserEmail('bengor@user.com'))
            ->shouldBeCalled()->willReturn($user);

        $this->user($command)->shouldReturn($user);
    }
}
