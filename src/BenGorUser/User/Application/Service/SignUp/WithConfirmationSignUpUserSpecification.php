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
 * With confirmation specification of sign up user command handler.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class WithConfirmationSignUpUserSpecification implements SignUpUserSpecification
{
    /**
     * {@inheritdoc}
     */
    public function email(SignUpUserCommand $aCommand)
    {
        return new UserEmail($aCommand->email());
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(User $aUser)
    {
        return $aUser;
    }
}
