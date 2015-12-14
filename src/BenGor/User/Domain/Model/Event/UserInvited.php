<?php

/*
 * This file is part of the BenGorUser library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\User\Domain\Model\Event;

use BenGor\User\Domain\Model\UserGuest;
use Ddd\Domain\DomainEvent;
use Ddd\Domain\Event\PublishableDomainEvent;

/**
 * User invited domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserInvited implements DomainEvent, PublishableDomainEvent
{
    /**
     * The guest user.
     *
     * @var UserGuest
     */
    private $guest;

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
     * Gets the occurred on.
     *
     * @return \DateTimeImmutable
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
