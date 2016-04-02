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

namespace spec\BenGor\User\Application\DataTransformer;

use BenGor\User\Application\DataTransformer\UserDataTransformer;
use BenGor\User\Application\DataTransformer\UserDtoDataTransformer;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of user DTO data transformer class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserDTODataTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UserDtoDataTransformer::class);
    }

    function it_implements_user_data_transformer()
    {
        $this->shouldImplement(UserDataTransformer::class);
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

        $user->id()->shouldBeCalled()->willReturn('user-id');
        $user->confirmationToken()->shouldBeCalled()->willReturn('confirmation-token');
        $user->createdOn()->shouldBeCalled()->willReturn($createdOn);
        $user->email()->shouldBeCalled()->willReturn('user@user.com');
        $user->lastLogin()->shouldBeCalled()->willReturn($lastLogin);
        $user->password()->shouldBeCalled()->willReturn('encoded-password');
        $user->rememberPasswordToken()->shouldBeCalled()->willReturn('remember-password-token');
        $user->updatedOn()->shouldBeCalled()->willReturn($updatedOn);

        $this->read()->shouldReturn([
            'id'                      => 'user-id',
            'confirmation_token'      => 'confirmation-token',
            'created_on'              => $createdOn,
            'email'                   => 'user@user.com',
            'last_login'              => $lastLogin,
            'password'                => 'encoded-password',
            'remember_password_token' => 'remember-password-token',
            'roles'                   => ['ROLE_USER'],
            'updated_on'              => $updatedOn,
        ]);
    }
}
