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

namespace spec\BenGorUser\User\Application\Command\ChangePassword;

use BenGorUser\User\Application\Command\ChangePassword\WithoutOldPasswordChangeUserPasswordCommand;
use BenGorUser\User\Application\Command\ChangePassword\WithoutOldPasswordChangeUserPasswordHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of WithoutOldPasswordChangeUserPasswordHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class WithoutOldPasswordChangeUserPasswordHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserPasswordEncoder $encoder)
    {
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(WithoutOldPasswordChangeUserPasswordHandler::class);
    }

    function it_changes_password(
        WithoutOldPasswordChangeUserPasswordCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->email()->shouldBeCalled()->willReturn('bengor@user.com');
        $repository->userOfEmail(new UserEmail('bengor@user.com'))
            ->shouldBeCalled()->willReturn($user);

        $command->newPlainPassword()->shouldBeCalled()->willReturn('new-plain-pass');

        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_changes_password_because_the_does_not_exist(
        WithoutOldPasswordChangeUserPasswordCommand $command,
        UserRepository $repository
    ) {
        $command->email()->shouldBeCalled()->willReturn('non-exist@user-email.com');
        $repository->userOfEmail(new UserEmail('non-exist@user-email.com'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
