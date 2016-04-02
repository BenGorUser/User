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

namespace BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types;

use BenGor\User\Domain\Model\UserRole;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Doctrine ODM MongoDB user role collection custom type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserRolesType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        $roles = array_map(function (UserRole $role) {
            return $role->role();
        }, $value);

        return json_encode($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, json_decode($value));

        return $userRoles;
    }
}
