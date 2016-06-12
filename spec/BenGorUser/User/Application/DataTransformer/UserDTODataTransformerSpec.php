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

namespace spec\BenGorUser\User\Application\DataTransformer;

use BenGorUser\User\Application\DataTransformer\UserDataTransformer;
use BenGorUser\User\Application\DataTransformer\UserDTODataTransformer;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserDTODataTransformer class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserDTODataTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UserDTODataTransformer::class);
    }

    function it_implements_user_data_transformer()
    {
        $this->shouldImplement(UserDataTransformer::class);
    }

    function it_transform_without_user_domain_class()
    {
        $this->read()->shouldReturn([]);

        $this->shouldThrow(
            new \InvalidArgumentException(sprintf('Expected instance of %s', User::class))
        )->duringWrite(['id' => 'not-user-domain-model-class']);
    }

    function it_transforms(
        User $user,
        \DateTimeImmutable $createdOn,
        \DateTimeImmutable $lastLogin,
        \DateTimeImmutable $updatedOn
    ) {
        $this->read()->shouldReturn([]);

        $this->write($user);

        $user->roles()->shouldBeCalled()->willReturn([new UserRole('ROLE_USER')]);

        $password = UserPassword::fromEncoded('encoded-password', 'user-password-salt');

        $user->id()->shouldBeCalled()->willReturn(new UserId('user-id'));
        $user->confirmationToken()->shouldBeCalled()->willReturn(null);
        $user->createdOn()->shouldBeCalled()->willReturn($createdOn);
        $user->email()->shouldBeCalled()->willReturn(new UserEmail('user@user.com'));
        $user->invitationToken()->shouldBeCalled()->willReturn(null);
        $user->lastLogin()->shouldBeCalled()->willReturn($lastLogin);
        $user->password()->shouldBeCalled()->willReturn($password);
        $user->rememberPasswordToken()->shouldBeCalled()->willReturn(null);
        $user->updatedOn()->shouldBeCalled()->willReturn($updatedOn);

        $this->read()->shouldReturn([
            'id'                      => 'user-id',
            'confirmation_token'      => null,
            'created_on'              => $createdOn,
            'email'                   => 'user@user.com',
            'invitation_token'        => null,
            'last_login'              => $lastLogin,
            'encoded_password'        => 'encoded-password',
            'salt'                    => 'user-password-salt',
            'remember_password_token' => null,
            'roles'                   => ['ROLE_USER'],
            'updated_on'              => $updatedOn,
        ]);
    }
}
