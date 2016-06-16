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

namespace BenGorUser\User\Infrastructure\Persistence;

use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Domain\Model\UserEventBus;

/**
 * In memory user repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class InMemoryUserRepository implements UserRepository
{
    /**
     * Array which contains the users.
     *
     * @var array
     */
    private $users;

    /**
     * The user event bus, it can be null
     *
     * @var UserEventBus|null
     */
    private $eventBus;

    /**
     * Constructor.
     *
     * @param UserEventBus|null $anEventBus The user event bus, it can be null
     */
    public function __construct(UserEventBus $anEventBus = null)
    {
        $this->users = [];
        $this->eventBus = $anEventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function userOfId(UserId $anId)
    {
        if (isset($this->users[$anId->id()])) {
            return $this->users[$anId->id()];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userOfEmail(UserEmail $anEmail)
    {
        foreach ($this->users as $user) {
            if (true === $user->email()->equals($anEmail)) {
                return $user;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userOfConfirmationToken(UserToken $aConfirmationToken)
    {
        foreach ($this->users as $user) {
            if (true === $user->confirmationToken()->equals($aConfirmationToken)) {
                return $user;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userOfInvitationToken(UserToken $anInvitationToken)
    {
        foreach ($this->users as $user) {
            if (true === $user->invitationToken()->equals($anInvitationToken)) {
                return $user;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userOfRememberPasswordToken(UserToken $aRememberPasswordToken)
    {
        foreach ($this->users as $user) {
            if (true === $user->rememberPasswordToken()->equals($aRememberPasswordToken)) {
                return $user;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $aUser)
    {
        $this->users[$aUser->id()->id()] = $aUser;

        if ($this->eventBus instanceof UserEventBus) {
            $this->handle($aUser->events());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $aUser)
    {
        unset($this->users[$aUser->id()->id()]);

        if ($this->eventBus instanceof UserEventBus) {
            $this->handle($aUser->events());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return count($this->users);
    }

    /**
     * Handles the given events with event bus.
     *
     * @param array $events A collection of user domain events
     */
    private function handle($events)
    {
        foreach ($events as $event) {
            $this->eventBus->handle($event);
        }
    }
}
