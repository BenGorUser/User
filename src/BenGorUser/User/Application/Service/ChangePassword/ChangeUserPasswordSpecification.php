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

namespace BenGorUser\User\Application\Service\ChangePassword;

use BenGorUser\User\Domain\Model\User;

/**
 * Change user password command handler specification.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface ChangeUserPasswordSpecification
{
    /**
     * Obtains the domain user from the command data.
     *
     * @param ChangeUserPasswordCommand $aCommand The command
     *
     * @return User
     */
    public function user(ChangeUserPasswordCommand $aCommand);
}
