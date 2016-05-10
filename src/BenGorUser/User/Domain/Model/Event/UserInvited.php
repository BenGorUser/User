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

use BenGorUser\User\Domain\Model\UserGuest;

/**
 * User invited domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserInvited implements UserEvent
{
    /**
     * The guest user.
     *
     * @var UserGuest
     */
    private $guest;

    /**
     * The occurred on.
     *
     * @var \DateTimeImmutable
     */
    private $occurredOn;

    /**
     * Constructor.
     *
     * @param UserGuest $aUserGuest The user
     */
    public function __construct(UserGuest $aUserGuest)
    {
        $this->guest = $aUserGuest;
        $this->occurredOn = new \DateTimeImmutable();
    }

    /**
     * Gets the guest user.
     *
     * @return UserGuest
     */
    public function userGuest()
    {
        return $this->guest;
    }

    /**
     * {@inheritdoc}
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
