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

namespace spec\BenGor\User\Application\Service;

use BenGor\User\Application\Service\LogInUserRequest;
use BenGor\User\Application\Service\LogInUserResponse;
use BenGor\User\Application\Service\LogInUserService;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\Exception\UserInactiveException;
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of log in user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogInUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encodedPassword')
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LogInUserService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_logs_the_user_in(UserRepository $repository, User $user)
    {
        $request = new LogInUserRequest('user@user.com', 'plainPassword');

        $user->login()->shouldBeCalled();
        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->password()->shouldBeCalled()->willReturn(
            UserPassword::fromPlain('plainPassword', new DummyUserPasswordEncoder('encodedPassword'))
        );

        $repository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request)->shouldReturnAnInstanceOf(LogInUserResponse::class);
    }

    function it_doesnt_log_if_user_not_enabled(UserRepository $repository, User $user)
    {
        $request = new LogInUserRequest('user@user.com', 'plainPassword');

        $user->login()->shouldNotBeCalled();
        $user->isEnabled()->shouldBeCalled()->willReturn(false);

        $repository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow(UserInactiveException::class)->duringExecute($request);
    }

    function it_doesnt_log_if_user_does_not_exist(UserRepository $repository, User $user)
    {
        $request = new LogInUserRequest('user@user.com', 'plainPassword');

        $user->login()->shouldNotBeCalled();

        $repository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn(null);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow(UserDoesNotExistException::class)->duringExecute($request);
    }

    function it_does_not_log_if_user_invalid_password(UserRepository $repository, User $user)
    {
        $encoder = new DummyUserPasswordEncoder('otherEncodedPassword', false);
        $this->beConstructedWith($repository, $encoder);

        $request = new LogInUserRequest('user@user.com', 'plainPassword');
        $password = UserPassword::fromPlain('otherPassword', $encoder);

        $repository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn($user);
        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->password()->shouldBeCalled()->willReturn($password);

        $this->shouldThrow(UserPasswordInvalidException::class)->duringExecute($request);
    }
}
