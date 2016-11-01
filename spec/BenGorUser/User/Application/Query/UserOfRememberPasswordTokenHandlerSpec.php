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

namespace spec\BenGorUser\User\Application\Query;

use BenGorUser\User\Application\DataTransformer\UserDataTransformer;
use BenGorUser\User\Application\Query\UserOfRememberPasswordTokenHandler;
use BenGorUser\User\Application\Query\UserOfRememberPasswordTokenQuery;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserTokenExpiredException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserOfRememberPasswordTokenHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfRememberPasswordTokenHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserDataTransformer $dataTransformer)
    {
        $this->beConstructedWith($repository, $dataTransformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserOfRememberPasswordTokenHandler::class);
    }

    function it_gets_the_user(
        UserOfRememberPasswordTokenQuery $query,
        UserRepository $repository,
        User $user,
        UserDataTransformer $dataTransformer,
        \DateTimeImmutable $createdOn,
        \DateTimeImmutable $lastLogin,
        \DateTimeImmutable $updatedOn
    ) {
        $query->rememberPasswordToken()->shouldBeCalled()->willReturn('remember-password-token');
        $token = new UserToken('remember-password-token');
        $repository->userOfRememberPasswordToken($token)->shouldBeCalled()->willReturn($user);
        $user->isRememberPasswordTokenExpired()->shouldBeCalled()->willReturn(false);
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

        $this->__invoke($query)->shouldReturn([
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
    }

    function it_does_not_get_the_user_because_the_remember_password_token_does_not_exist(
        UserRepository $repository,
        UserOfRememberPasswordTokenQuery $query
    ) {
        $query->rememberPasswordToken()->shouldBeCalled()->willReturn('remember-password-token');
        $token = new UserToken('remember-password-token');
        $repository->userOfRememberPasswordToken($token)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($query);
    }

    function it_does_not_get_the_user_because_the_remember_password_token_is_expired(
        UserRepository $repository,
        UserOfRememberPasswordTokenQuery $query,
        User $user
    ) {
        $query->rememberPasswordToken()->shouldBeCalled()->willReturn('remember-password-token');
        $token = new UserToken('remember-password-token');
        $repository->userOfRememberPasswordToken($token)->shouldBeCalled()->willReturn($user);
        $user->isRememberPasswordTokenExpired()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(UserTokenExpiredException::class)->during__invoke($query);
    }
}
