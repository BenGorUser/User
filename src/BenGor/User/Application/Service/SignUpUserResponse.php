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

namespace BenGor\User\Application\Service;

use BenGor\User\Domain\Model\User;

/**
 * User sign up response class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class SignUpUserResponse
{
    /**
     * The user.
     *
     * @var User
     */
    private $user;

    /**
     * Constructor.
     *
     * @param User $aUser The user
     */
    public function __construct(User $aUser)
    {
        $this->user = $aUser;
    }

    /**
     * Gets the user.
     *
     * @return User
     */
    public function user()
    {
        return $this->user;
    }
}
