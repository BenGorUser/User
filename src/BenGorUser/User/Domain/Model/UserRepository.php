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
     * Finds all the users.
     *
     * @return User[]
     */
    public function all();

    /**
     * Finds the user of given email.
     *
     * @param UserEmail $anEmail The user email
     *
     * @return User
     */
    public function userOfEmail(UserEmail $anEmail);

    /**
     * Finds the user of given confirmation token.
     *
     * @param UserToken $aConfirmationToken The user confirmation token
     *
     * @return User
     */
    public function userOfConfirmationToken(UserToken $aConfirmationToken);

    /**
     * Finds the user of given invitation token.
     *
     * @param UserToken $anInvitationToken The user invitation token
     *
     * @return User
     */
    public function userOfInvitationToken(UserToken $anInvitationToken);

    /**
     * Finds the user of given remember password token.
     *
     * @param UserToken $aRememberPasswordToken The remember password token
     *
     * @return User
     */
    public function userOfRememberPasswordToken(UserToken $aRememberPasswordToken);

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
}
