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

use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordCommand;
use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of ChangeUserPasswordHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserPasswordEncoder $encoder)
    {
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeUserPasswordHandler::class);
    }

    function it_changes_password(ChangeUserPasswordCommand $command, UserRepository $repository, User $user)
    {
        $encoder = new DummyUserPasswordEncoder('encoded-pass');
        $userPassword = UserPassword::fromPlain('old-plain-pass', $encoder, 'dummy-salt');

        $command->id()->shouldBeCalled()->willReturn('user-id');
        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);
        $user->password()->shouldBeCalled()->willReturn($userPassword);
        $command->oldPlainPassword()->shouldBeCalled()->willReturn('old-plain-pass');
        $command->newPlainPassword()->shouldBeCalled()->willReturn('new-plain-pass');

        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_change_password_because_user_does_not_exist(
        ChangeUserPasswordCommand $command,
        UserRepository $repository
    ) {
        $command->id()->shouldBeCalled()->willReturn('non-exist-user-id');
        $repository->userOfId(new UserId('non-exist-user-id'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }

    function it_does_not_change_password_because_user_password_is_invalid(
        ChangeUserPasswordCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $encoder = new DummyUserPasswordEncoder('encoded-pass', false);
        $userPassword = UserPassword::fromPlain('plain-pass', $encoder, 'dummy-salt');
        $this->beConstructedWith($repository, $encoder);
        $command->id()->shouldBeCalled()->willReturn('user-id');
        $repository->userOfId(new UserId('user-id'))->shouldBeCalled()->willReturn($user);
        $user->password()->shouldBeCalled()->willReturn($userPassword);
        $command->oldPlainPassword()->shouldBeCalled()->willReturn('old-plain-pass');

        $this->shouldThrow(UserPasswordInvalidException::class)->during__invoke($command);
    }
}
