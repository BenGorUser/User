<?php

/*
 * This file is part of the User library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\User\Application\Service;

/**
 * Change user password using remember password token request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class ChangeUserPasswordUsingRememberPasswordTokenRequest
{
    /**
     * The new plain password.
     *
     * @var string
     */
    private $newPlainPassword;

    /**
     * The password remember token.
     *
     * @var string
     */
    private $rememberPasswordToken;

    /**
     * Constructor.
     *
     * @param string $aNewPlainPassword      The new plain password
     * @param string $aRememberPasswordToken The password remember token
     */
    public function __construct($aNewPlainPassword, $aRememberPasswordToken)
    {
        $this->newPlainPassword = $aNewPlainPassword;
        $this->rememberPasswordToken = $aRememberPasswordToken;
    }

    /**
     * Gets the new plain password.
     *
     * @return string
     */
    public function newPlainPassword()
    {
        return $this->newPlainPassword;
    }

    /**
     * Gets the password remember token.
     *
     * @return string
     */
    public function rememberPasswordToken()
    {
        return $this->rememberPasswordToken;
    }
}
