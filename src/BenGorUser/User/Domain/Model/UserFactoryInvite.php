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
 * User invite factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserFactoryInvite
{
    /**
     * Invites the user with given id, email.
     *
     * @param UserId     $anId    The user id
     * @param UserEmail  $anEmail The user email
     * @param UserRole[] $roles   List of roles
     *
     * @return User
     */
    public function build(UserId $anId, UserEmail $anEmail, array $roles);
}
