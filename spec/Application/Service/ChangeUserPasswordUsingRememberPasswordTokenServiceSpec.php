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

use BenGor\User\Application\Service\ChangeUserPasswordUsingRememberPasswordTokenRequest;
use BenGor\User\Application\Service\ChangeUserPasswordUsingRememberPasswordTokenService;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of change user password using remember password token service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordUsingRememberPasswordTokenServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeUserPasswordUsingRememberPasswordTokenService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_restores_password(UserRepository $repository, User $user)
    {
        $request = new ChangeUserPasswordUsingRememberPasswordTokenRequest(
            'newPassword', 'dummy-remember-password-token'
        );
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $oldPassword = UserPassword::fromPlain('oldPassword', $encoder);

        $repository->userOfRememberPasswordToken($request->rememberPasswordToken())
            ->shouldBeCalled()->willReturn($user);

        $user->password()->shouldBeCalled()->willReturn($oldPassword);
        $user->changePassword($oldPassword, Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_restore_password_because_user_does_not_exist(UserRepository $repository)
    {
        $request = new ChangeUserPasswordUsingRememberPasswordTokenRequest(
            'newPassword', 'dummy-remember-password-token'
        );

        $repository->userOfRememberPasswordToken($request->rememberPasswordToken())->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new UserDoesNotExistException())->duringExecute($request);
    }
}
