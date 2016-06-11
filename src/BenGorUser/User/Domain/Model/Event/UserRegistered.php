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
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserToken;

/**
 * User registered domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserRegistered implements UserEvent
{
    /**
     * The user id.
     *
     * @var UserId
     */
    private $userId;

    /**
     * The user email.
     *
     * @var UserEmail
     */
    private $email;

    /**
     * The confirmation token.
     *
     * @var UserToken|null
     */
    private $confirmationToken;

    /**
     * The occurred on.
     *
     * @var \DateTimeImmutable
     */
    private $occurredOn;

    /**
     * Constructor.
     *
     * @param UserId         $aUserId            The user id
     * @param UserEmail      $anEmail            The email
     * @param UserToken|null $aConfirmationToken The confirmation token
     */
    public function __construct(UserId $aUserId, UserEmail $anEmail, UserToken $aConfirmationToken = null)
    {
        $this->userId = $aUserId;
        $this->email = $anEmail;
        $this->confirmationToken = $aConfirmationToken;
        $this->occurredOn = new \DateTimeImmutable();
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->userId;
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
     * Gets the confirmation token.
     *
     * @return UserToken|null
     */
    public function confirmationToken()
    {
        return $this->confirmationToken;
    }
}
