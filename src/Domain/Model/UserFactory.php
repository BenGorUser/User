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

namespace BenGor\User\Domain\Model;

/**
 * User factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserFactory
{
    /**
     * Registers the user with given id, email and password.
     *
     * @param UserId       $anId      The user id
     * @param UserEmail    $anEmail   The user email
     * @param UserPassword $aPassword The user password
     * @param array        $roles     List of roles
     *
     * @return User
     */
    public function register(UserId $anId, UserEmail $anEmail, UserPassword $aPassword, array $roles);
}
