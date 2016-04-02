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

namespace spec\BenGor\User\Application\Service\LogIn;

use BenGor\User\Application\DataTransformer\UserDataTransformer;
use BenGor\User\Application\Service\LogIn\LogInUserRequest;
use BenGor\User\Application\Service\LogIn\LogInUserService;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\Exception\UserInactiveException;
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of log in user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogInUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserDataTransformer $dataTransformer)
    {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encodedPassword'),
            $dataTransformer
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LogInUserService::class);
    }

    function it_logs_the_user_in(
        LogInUserRequest $request,
        UserRepository $repository,
        User $user,
        UserDataTransformer $dataTransformer,
        \DateTimeImmutable $createdOn,
        \DateTimeImmutable $lastLogin,
        \DateTimeImmutable $updatedOn
    ) {
        $password = UserPassword::fromPlain('plainPassword', new DummyUserPasswordEncoder('encodedPassword'));

        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $request->password()->shouldBeCalled()->willReturn('plainPassword');

        $user->login()->shouldBeCalled();
        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->password()->shouldBeCalled()->willReturn($password);

        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();
        $dataTransformer->write($user)->shouldBeCalled();
        $dataTransformer->read()->shouldBeCalled()->willReturn([
            'id'                      => 'user-id',
            'confirmation_token'      => null,
            'created_on'              => $createdOn,
            'email'                   => 'user@user.com',
            'last_login'              => $lastLogin,
            'password'                => 'encoded-password',
            'remember_password_token' => null,
            'roles'                   => ['ROLE_USER'],
            'updated_on'              => $updatedOn,
        ]);

        $this->execute($request);
    }

    function it_does_not_log_if_user_not_enabled(UserRepository $repository, User $user, LogInUserRequest $request)
    {
        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $request->password()->shouldBeCalled()->willReturn('plainPassword');

        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn($user);
        $user->isEnabled()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(UserInactiveException::class)->duringExecute($request);
    }

    function it_does_not_log_if_user_does_not_exist(UserRepository $repository, LogInUserRequest $request)
    {
        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $request->password()->shouldBeCalled()->willReturn('plainPassword');

        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringExecute($request);
    }

    function it_does_not_log_if_user_invalid_password(
        UserRepository $repository,
        UserDataTransformer $dataTransformer,
        User $user,
        LogInUserRequest $request
    ) {
        $encoder = new DummyUserPasswordEncoder('otherEncodedPassword', false);
        $this->beConstructedWith($repository, $encoder, $dataTransformer);

        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $request->password()->shouldBeCalled()->willReturn('plainPassword');
        $password = UserPassword::fromPlain('otherPassword', $encoder);

        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn($user);
        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->password()->shouldBeCalled()->willReturn($password);

        $this->shouldThrow(UserPasswordInvalidException::class)->duringExecute($request);
    }
}
