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

namespace BenGor\User\Application\Service\ChangePassword;

use BenGor\User\Domain\Model\User;

/**
 * Change user password service specification.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface SpecificationChangeUserPassword
{
    /**
     * Obtains the domain user from request data.
     *
     * @param ChangeUserPasswordRequest $request The request
     *
     * @return User
     */
    public function user(ChangeUserPasswordRequest $request);
}
