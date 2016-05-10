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
 * User guest factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface UserGuestFactory
{
    /**
     * Registers the user guest with given id, and email.
     *
     * @param UserGuestId $anId    The user guest id
     * @param UserEmail   $anEmail The user email
     *
     * @return UserGuest
     */
    public function register(UserGuestId $anId, UserEmail $anEmail);
}
