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

namespace BenGorUser\User\Infrastructure\Domain\Model;

use BenGorUser\User\Domain\Model\Event\UserEvent;

/**
 * User event bus class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserEventBus
{
    /**
     * Publishes the given domain event.
     *
     * @param UserEvent $anEvent The domain event
     */
    public function handle(UserEvent $anEvent);
}
