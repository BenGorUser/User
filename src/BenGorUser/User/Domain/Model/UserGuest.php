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

use BenGorUser\User\Domain\Model\Event\UserInvited;

/**
 * User guest domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserGuest
{
    use UserEventStore;

    /**
     * The user guest id.
     *
     * @var UserGuestId
     */
    protected $id;

    /**
     * Created on.
     *
     * @var \DateTimeInterface
     */
    protected $createdOn;

    /**
     * The email.
     *
     * @var UserEmail
     */
    protected $email;

    /**
     * The invitation token.
     *
     * @var UserToken
     */
    protected $invitationToken;

    /**
     * Constructor.
     *
     * @param UserGuestId $anId    The id
     * @param UserEmail   $anEmail The email
     */
    public function __construct(UserGuestId $anId, UserEmail $anEmail)
    {
        $this->id = $anId;
        $this->email = $anEmail;
        $this->createdOn = new \DateTimeImmutable();
        $this->regenerateInvitationToken();
    }

    /**
     * Gets the id.
     *
     * @return UserGuestId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Gets the created on.
     *
     * @return \DateTimeInterface
     */
    public function createdOn()
    {
        return $this->createdOn;
    }

    /**
     * Gets the email.
     *
     * @return UserEmail
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Gets the invitation token.
     *
     * @return UserToken
     */
    public function invitationToken()
    {
        return $this->invitationToken;
    }

    /**
     * Updates the invitation token.
     *
     * @return UserToken
     */
    public function regenerateInvitationToken()
    {
        $this->invitationToken = new UserToken();

        $this->publish(
            new UserInvited(
                $this->id,
                $this->email,
                $this->invitationToken
            )
        );
    }
}
