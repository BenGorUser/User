<?php

namespace BenGor\User\Domain\Model;

use Ddd\Domain\DomainEvent;
use Ddd\Domain\Event\PublishableDomainEvent;
use BenGor\User\Model\User;

/**
 * User logged in domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserLoggedIn implements DomainEvent, PublishableDomainEvent
{
    /**
     * The user.
     *
     * @var User
     */
    private $user;

    /**
     * Constructor.
     *
     * @param User $aUser The user
     */
    public function __construct(User $aUser)
    {
        $this->user = $aUser;
        $this->occurredOn = new \DateTime();
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
     * Gets the occurred on.
     *
     * @return \DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
