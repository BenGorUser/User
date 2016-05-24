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

namespace BenGorUser\User\Domain\Model\Event;

use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuestId;
use BenGorUser\User\Domain\Model\UserToken;

/**
 * User invited domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserInvited implements UserEvent
{
    /**
     * The user guest id.
     *
     * @var UserGuestId
     */
    private $userGuestId;

    /**
     * The user email.
     *
     * @var UserEmail
     */
    private $email;

    /**
     * The confirmation token.
     *
     * @var UserToken
     */
    private $invitationToken;

    /**
     * The occurred on.
     *
     * @var \DateTimeImmutable
     */
    private $occurredOn;

    /**
     * Constructor.
     *
     * @param UserGuestId $aUserGuestId      The user guest id
     * @param UserEmail   $anEmail           The email
     * @param UserToken   $anInvitationToken The invitation token
     */
    public function __construct(UserGuestId $aUserGuestId, UserEmail $anEmail, UserToken $anInvitationToken)
    {
        $this->userGuestId = $aUserGuestId;
        $this->email = $anEmail;
        $this->invitationToken = $anInvitationToken;
        $this->occurredOn = new \DateTimeImmutable();
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->userGuestId;
    }

    /**
     * {@inheritdoc}
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function occurredOn()
    {
        return $this->occurredOn;
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
}
