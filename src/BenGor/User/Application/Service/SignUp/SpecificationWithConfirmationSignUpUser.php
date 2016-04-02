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
 * With confirmation specification of sign up user service.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SpecificationWithConfirmationSignUpUser implements SpecificationSignUpUser
{
    /**
     * {@inheritdoc}
     */
    public function email(SignUpUserRequest $request)
    {
        return new UserEmail($request->email());
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(User $aUser)
    {
        return $aUser;
    }
}
