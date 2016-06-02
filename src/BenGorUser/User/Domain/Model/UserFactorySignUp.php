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

namespace BenGorUser\User\Domain\Model;

/**
 * User sign up factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserFactorySignUp
{
    /**
     * Sing up the user with given id, email, password and roles.
     *
     * @param UserId       $anId      The user id
     * @param UserEmail    $anEmail   The user email
     * @param UserPassword $aPassword The user password
     * @param array        $roles     List of roles
     *
     * @return User
     */
    public function build(UserId $anId, UserEmail $anEmail, UserPassword $aPassword, array $roles);
}
