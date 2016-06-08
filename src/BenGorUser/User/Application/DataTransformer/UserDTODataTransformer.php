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

namespace BenGorUser\User\Application\DataTransformer;

use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserRole;

/**
 * User DTO data transformer.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserDTODataTransformer implements UserDataTransformer
{
    /**
     * The domain user.
     *
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function write($aUser)
    {
        if (!$aUser instanceof User) {
            throw new \InvalidArgumentException(sprintf('Expected instance of %s', User::class));
        }
        $this->user = $aUser;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        if (null === $this->user) {
            return [];
        }

        $roles = array_map(function (UserRole $role) {
            return $role->role();
        }, $this->user->roles());

        $confirmationToken = !$this->user->confirmationToken()
            ? null
            : $this->user->confirmationToken()->token();
        $rememberPasswordToken = !$this->user->rememberPasswordToken()
            ? null
            : $this->user->rememberPasswordToken()->token();

        return [
            'id'                      => $this->user->id()->id(),
            'confirmation_token'      => $confirmationToken,
            'created_on'              => $this->user->createdOn(),
            'email'                   => $this->user->email()->email(),
            'last_login'              => $this->user->lastLogin(),
            'encoded_password'        => $this->user->password()->encodedPassword(),
            'salt'                    => $this->user->password()->salt(),
            'remember_password_token' => $rememberPasswordToken,
            'roles'                   => $roles,
            'updated_on'              => $this->user->updatedOn(),
        ];
    }
}
