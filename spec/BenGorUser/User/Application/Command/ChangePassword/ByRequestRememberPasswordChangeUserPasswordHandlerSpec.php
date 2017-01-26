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

use BenGorUser\User\Application\Command\ChangePassword\ByRequestRememberPasswordChangeUserPasswordCommand;
use BenGorUser\User\Application\Command\ChangePassword\ByRequestRememberPasswordChangeUserPasswordHandler;
use BenGorUser\User\Domain\Model\Exception\UserTokenExpiredException;
use BenGorUser\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of ByRequestRememberPasswordChangeUserPasswordHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByRequestRememberPasswordChangeUserPasswordHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserPasswordEncoder $encoder)
    {
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ByRequestRememberPasswordChangeUserPasswordHandler::class);
    }

    function it_changes_password(
        ByRequestRememberPasswordChangeUserPasswordCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->rememberPasswordToken()->shouldBeCalled()->willReturn('remember-password-token');
        $repository->userOfRememberPasswordToken(Argument::type(UserToken::class))->shouldBeCalled()->willReturn($user);
        $command->newPlainPassword()->shouldBeCalled()->willReturn('new-plain-pass');
        $user->isRememberPasswordTokenExpired()->shouldBeCalled()->willReturn(false);
        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_change_password_because_token_does_not_exist(
        ByRequestRememberPasswordChangeUserPasswordCommand $command,
        UserRepository $repository
    ) {
        $command->rememberPasswordToken()->shouldBeCalled()->willReturn('non-exist-remember-password-token');
        $repository->userOfRememberPasswordToken(Argument::type(UserToken::class))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserTokenNotFoundException::class)->during__invoke($command);
    }

    function it_does_not_change_password_because_the_token_is_expired(
        ByRequestRememberPasswordChangeUserPasswordCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->rememberPasswordToken()->shouldBeCalled()->willReturn('non-exist-remember-password-token');
        $repository->userOfRememberPasswordToken(Argument::type(UserToken::class))->shouldBeCalled()->willReturn($user);
        $user->isRememberPasswordTokenExpired()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(UserTokenExpiredException::class)->during__invoke($command);
    }
}
