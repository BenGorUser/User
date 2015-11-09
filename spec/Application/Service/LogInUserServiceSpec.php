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
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\BenGor\User\Domain\Model\DummyUserPasswordEncoder;

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
        $this->shouldHaveType('BenGor\User\Application\Service\LogInUserService');
    }

    function it_implements_application_service()
    {
        $this->shouldImplement('Ddd\Application\Service\ApplicationService');
    }

    function it_logs_the_user_in(UserRepository $repository, User $user)
    {
        $request = new LogInUserRequest('user@user.com', 'plainPassword');

        $user->login()->shouldBeCalled();
        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->password()->shouldBeCalled()->willReturn(
            UserPassword::fromPlain('plainPassword', new DummyUserPasswordEncoder('encodedPassword'))
        );

        $repository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request)->shouldReturnAnInstanceOf('BenGor\User\Application\Service\LogInUserResponse');
    }

    function it_doesnt_log_if_user_not_enabled(UserRepository $repository, User $user)
    {
        $request = new LogInUserRequest('user@user.com', 'plainPassword');

        $user->login()->shouldNotBeCalled();
        $user->isEnabled()->shouldBeCalled()->willReturn(false);

        $repository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserInactiveException')
            ->duringExecute($request);
    }

    function it_doesnt_log_if_user_does_not_exist(UserRepository $repository, User $user)
    {
        $request = new LogInUserRequest('user@user.com', 'plainPassword');

        $user->login()->shouldNotBeCalled();

        $repository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn(null);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserDoesNotExistException')
            ->duringExecute($request);
    }

    function it_does_not_log_if_user_invalid_password(UserRepository $repository, User $user)
    {
        $request = new LogInUserRequest('user@user.com', 'plainPassword');

        $user->login()->shouldNotBeCalled();
        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->password()->shouldBeCalled()->willReturn(
            UserPassword::fromPlain('otherPassword', new DummyUserPasswordEncoder('otherEncodedPassword'))
        );

        $repository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->willReturn($user);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserInvalidPasswordException')
            ->duringExecute($request);
    }
}
