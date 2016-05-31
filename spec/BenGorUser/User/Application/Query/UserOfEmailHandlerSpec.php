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
use BenGorUser\User\Application\Query\UserOfEmailHandler;
use BenGorUser\User\Application\Query\UserOfEmailQuery;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserOfEmailHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfEmailHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserDataTransformer $dataTransformer)
    {
        $this->beConstructedWith($repository, $dataTransformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserOfEmailHandler::class);
    }

    function it_gets_the_user(
        UserOfEmailQuery $query,
        UserRepository $repository,
        User $user,
        UserDataTransformer $dataTransformer,
        \DateTimeImmutable $createdOn,
        \DateTimeImmutable $lastLogin,
        \DateTimeImmutable $updatedOn
    ) {
        $query->email()->shouldBeCalled()->willReturn('bengor@user.com');
        $email = new UserEmail('bengor@user.com');
        $repository->userOfEmail($email)->shouldBeCalled()->willReturn($user);
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

    function it_does_not_get_the_user_because_the_email_does_not_exist(
        UserRepository $repository,
        UserOfEmailQuery $query
    ) {
        $query->email()->shouldBeCalled()->willReturn('bengor@user.com');
        $email = new UserEmail('bengor@user.com');
        $repository->userOfEmail($email)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($query);
    }
}
