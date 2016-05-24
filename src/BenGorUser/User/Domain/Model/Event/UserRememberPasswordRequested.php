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
 * User remember password request domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserRememberPasswordRequested implements UserEvent
{
    /**
     * The user id.
     *
     * @var UserId
     */
    private $userId;

    /**
     * The email.
     *
     * @var UserEmail
     */
    private $email;

    /**
     * The occurred on.
     *
     * @var \DateTimeImmutable
     */
    private $occurredOn;

    /**
     * The remember password token.
     *
     * @var UserToken
     */
    private $rememberPasswordToken;

    /**
     * Constructor.
     *
     * @param UserId    $aUserId                The user id
     * @param UserEmail $anEmail                The email
     * @param UserToken $aRememberPasswordToken The remember password token
     */
    public function __construct(UserId $aUserId, UserEmail $anEmail, UserToken $aRememberPasswordToken)
    {
        $this->userId = $aUserId;
        $this->email = $anEmail;
        $this->rememberPasswordToken = $aRememberPasswordToken;
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
     * Gets the remember password token.
     *
     * @return UserToken
     */
    public function rememberPasswordToken()
    {
        return $this->rememberPasswordToken;
    }
}
