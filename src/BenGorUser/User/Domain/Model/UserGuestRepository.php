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
 * User guest repository domain interface.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserGuestRepository
{
    /**
     * Finds the user guest of given id.
     *
     * @param UserGuestId $anId The user id
     *
     * @return UserGuest
     */
    public function userGuestOfId(UserGuestId $anId);

    /**
     * Finds the user guest of given email.
     *
     * @param UserEmail $anEmail The user email
     *
     * @return UserGuest
     */
    public function userGuestOfEmail(UserEmail $anEmail);

    /**
     * Finds the user guest of given invitation token.
     *
     * @param UserToken $anInvitationToken The inviation token
     *
     * @return UserGuest
     */
    public function userGuestOfInvitationToken(UserToken $anInvitationToken);

    /**
     * Persists the given user guest.
     *
     * @param UserGuest $aUserGuest The user guest
     */
    public function persist(UserGuest $aUserGuest);

    /**
     * Removes the given user guest.
     *
     * @param UserGuest $aUserGuest The user guest
     */
    public function remove(UserGuest $aUserGuest);

    /**
     * Counts the user guests.
     *
     * @return int
     */
    public function size();

    /**
     * Gets the next user guest id.
     *
     * @return UserGuestId
     */
    public function nextIdentity();
}
