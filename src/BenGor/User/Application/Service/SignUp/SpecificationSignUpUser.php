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

namespace BenGor\User\Application\Service\SignUp;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;

/**
 * Sign up user service specification.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface SpecificationSignUpUser
{
    /**
     * Obtains the domain user email from request.
     *
     * @param SignUpUserRequest $request The request
     *
     * @return UserEmail
     */
    public function email(SignUpUserRequest $request);

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
