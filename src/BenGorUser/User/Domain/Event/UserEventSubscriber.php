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

namespace BenGorUser\User\Domain\Event;

use BenGorUser\User\Domain\Model\Event\UserEvent;

/**
 * User base domain event subscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface UserEventSubscriber
{
    /**
     * Handles the given user event.
     *
     * @param UserEvent $anEvent The domain event
     */
    public function handle(UserEvent $anEvent);

    /**
     * {@inheritdoc}
     *
     * @param UserEvent $anEvent The domain event
     */
    public function isSubscribedTo(UserEvent $anEvent);
}
