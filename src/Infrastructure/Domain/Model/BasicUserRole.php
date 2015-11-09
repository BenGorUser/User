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

namespace BenGor\User\Infrastructure\Domain\Model;

use BenGor\User\Domain\Model\UserRole as BaseUserRole;

/**
 * User role domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class BasicUserRole extends BaseUserRole
{
    /**
     * {@inheritdoc}
     */
    protected function roles()
    {
        return ['ROLE_USER', 'ROLE_SUPER_ADMIN'];
    }
}
