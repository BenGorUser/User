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

namespace BenGorUser\User\Domain\Model\Exception;

/**
 * User role already revoked exception class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserRoleAlreadyRevokedException extends \InvalidArgumentException
{
}
