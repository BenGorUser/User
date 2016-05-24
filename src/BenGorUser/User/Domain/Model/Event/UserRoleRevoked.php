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
use BenGorUser\User\Domain\Model\UserRole;

/**
 * User role revoked domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserRoleRevoked implements UserEvent
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
     * The revoked role.
     *
     * @var UserRole
     */
    private $revokedRole;

    /**
     * Constructor.
     *
     * @param UserId    $aUserId      The user id
     * @param UserEmail $anEmail      The email
     * @param UserRole  $aRevokedRole The revoked role
     */
    public function __construct(UserId $aUserId, UserEmail $anEmail, UserRole $aRevokedRole)
    {
        $this->userId = $aUserId;
        $this->email = $anEmail;
        $this->revokedRole = $aRevokedRole;
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
     * Gets the revoked role.
     *
     * @return UserRole
     */
    public function role()
    {
        return $this->revokedRole;
    }
}
