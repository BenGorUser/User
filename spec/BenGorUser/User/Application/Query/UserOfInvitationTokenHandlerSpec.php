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
use BenGorUser\User\Application\Query\UserOfInvitationTokenHandler;
use BenGorUser\User\Application\Query\UserOfInvitationTokenQuery;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserTokenExpiredException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserOfInvitationTokenHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfInvitationTokenHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserDataTransformer $dataTransformer)
    {
        $this->beConstructedWith($repository, $dataTransformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserOfInvitationTokenHandler::class);
    }

    function it_gets_the_user(
        UserOfInvitationTokenQuery $query,
        UserRepository $repository,
        User $user,
        UserDataTransformer $dataTransformer,
        \DateTimeImmutable $createdOn,
        \DateTimeImmutable $lastLogin,
        \DateTimeImmutable $updatedOn
    ) {
        $query->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $token = new UserToken('invitation-token');
        $repository->userOfInvitationToken($token)->shouldBeCalled()->willReturn($user);
        $user->isInvitationTokenExpired()->shouldBeCalled()->willReturn(false);
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

    function it_does_not_get_the_user_because_the_invitation_token_does_not_exist(
        UserRepository $repository,
        UserOfInvitationTokenQuery $query
    ) {
        $query->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $token = new UserToken('invitation-token');
        $repository->userOfInvitationToken($token)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($query);
    }

    function it_does_not_get_the_user_because_the_invitation_token_is_expired(
        UserRepository $repository,
        UserOfInvitationTokenQuery $query,
        User $user
    ) {
        $query->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $token = new UserToken('invitation-token');
        $repository->userOfInvitationToken($token)->shouldBeCalled()->willReturn($user);
        $user->isInvitationTokenExpired()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(UserTokenExpiredException::class)->during__invoke($query);
    }
}
