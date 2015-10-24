<?php

/*
 * This file is part of the User library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\User\Domain\Model;

/**
 * User repository domain interface.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserRepository
{
    /**
     * Finds the user of given id.
     *
     * @param UserId $anId The user id
     *
     * @return User
     */
    public function userOfId(UserId $anId);

    /**
     * Finds the user of given email.
     *
     * @param UserEmail $anEmail The user email
     *
     * @return User
     */
    public function userOfEmail(UserEmail $anEmail);

    /**
     * Finds the user of given email.
     *
     * @param UserConfirmationToken $aConfirmationToken The user confirmationToken
     *
     * @return User
     */
    public function userOfConfirmationToken(UserConfirmationToken $aConfirmationToken);

    /**
     * Finds users with the given specification.
     *
     * @param object $specification The specification
     *
     * @return User[]
     */
    public function query($specification);

    /**
     * Persists the given user.
     *
     * @param User $aUser The user
     */
    public function persist(User $aUser);

    /**
     * Removes the given user.
     *
     * @param User $aUser The user
     */
    public function remove(User $aUser);

    /**
     * Counts the users.
     *
     * @return int
     */
    public function size();

    /**
     * Gets the next user id.
     *
     * @return UserId
     */
    public function nextIdentity();
}
