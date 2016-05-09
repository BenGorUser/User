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

namespace BenGorUser\User\Infrastructure\Persistence\InMemory;

use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestId;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Domain\Model\UserEventBus;

/**
 * In memory user guest repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class InMemoryUserGuestRepository implements UserGuestRepository
{
    /**
     * Array which contains the guest users.
     *
     * @var array
     */
    private $guestUsers;

    /**
     * The user event bus.
     *
     * @var UserEventBus
     */
    private $eventBus;

    /**
     * Constructor.
     *
     * @param UserEventBus $anEventBus The user event bus
     */
    public function __construct(UserEventBus $anEventBus)
    {
        $this->guestUsers = [];
        $this->eventBus = $anEventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfId(UserGuestId $anId)
    {
        if (isset($this->guestUsers[$anId->id()])) {
            return $this->guestUsers[$anId->id()];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfEmail(UserEmail $anEmail)
    {
        foreach ($this->guestUsers as $user) {
            if (true === $user->email()->equals($anEmail)) {
                return $user;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfInvitationToken(UserToken $anInvitationToken)
    {
        foreach ($this->guestUsers as $user) {
            if (true === $user->invitationToken()->equals($anInvitationToken)) {
                return $user;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function persist(UserGuest $aUserGuest)
    {
        $this->guestUsers[$aUserGuest->id()->id()] = $aUserGuest;

        foreach ($aUserGuest->events() as $event) {
            $this->eventBus->handle($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(UserGuest $aUserGuest)
    {
        unset($this->guestUsers[$aUserGuest->id()->id()]);

        foreach ($aUserGuest->events() as $event) {
            $this->eventBus->handle($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return count($this->guestUsers);
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserGuestId();
    }
}
