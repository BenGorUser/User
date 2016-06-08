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

namespace spec\BenGorUser\User\Application\Command\RequestRememberPassword;

use BenGorUser\User\Application\Command\RequestRememberPassword\RequestRememberPasswordCommand;
use BenGorUser\User\Application\Command\RequestRememberPassword\RequestRememberPasswordHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of RequestRememberPasswordHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RequestRememberPasswordHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestRememberPasswordHandler::class);
    }

    function it_requests_remember_password(
        RequestRememberPasswordCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn($user);

        $user->rememberPassword()->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_request_remember_password_because_user_does_not_exist(
        RequestRememberPasswordCommand $command,
        UserRepository $repository
    ) {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
