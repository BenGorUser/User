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

/**
 * User event base domain event class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserEvent
{
    /**
     * Gets the id.
     *
     * @return UserId
     */
    public function id();

    /**
     * Gets the email.
     *
     * @return UserEmail
     */
    public function email();

    /**
     * Gets the occurred on.
     *
     * @return \DateTimeImmutable
     */
    public function occurredOn();
}
