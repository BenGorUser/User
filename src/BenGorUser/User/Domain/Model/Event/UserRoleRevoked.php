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

use BenGorUser\User\Domain\Model\User;

/**
 * User role revoked domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserRoleRevoked implements UserEvent
{
    /**
     * The user.
     *
     * @var User
     */
    private $user;

    /**
     * The occurred on.
     *
     * @var \DateTimeImmutable
     */
    private $occurredOn;

    /**
     * Constructor.
     *
     * @param User $aUser The user
     */
    public function __construct(User $aUser)
    {
        $this->user = $aUser;
        $this->occurredOn = new \DateTimeImmutable();
    }

    /**
     * Gets the user.
     *
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
