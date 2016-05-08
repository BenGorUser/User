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

namespace BenGorUser\User\Application\Service\SignUp;

use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;

/**
 * Sign up user command handler specification.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface SignUpUserSpecification
{
    /**
     * Obtains the domain user email from command.
     *
     * @param SignUpUserCommand $aCommand The command
     *
     * @return UserEmail
     */
    public function email(SignUpUserCommand $aCommand);

    /**
     * Extension point to put some logic that need
     * to be execute before persist statement.
     *
     * @param User $aUser The user
     *
     * @return User
     */
    public function prePersist(User $aUser);
}
