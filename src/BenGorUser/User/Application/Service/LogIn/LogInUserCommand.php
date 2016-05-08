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

namespace BenGorUser\User\Application\Service\LogIn;

/**
 * User login command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogInUserCommand
{
    /**
     * The user email.
     *
     * @var string
     */
    private $email;

    /**
     * The user plain password.
     *
     * @var string
     */
    private $plainPassword;

    /**
     * Constructor.
     *
     * @param string $anEmail        The user email
     * @param string $aPlainPassword The user plain password
     */
    public function __construct($anEmail, $aPlainPassword)
    {
        $this->email = $anEmail;
        $this->plainPassword = $aPlainPassword;
    }

    /**
     * Gets the user email.
     *
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Gets the user plain password.
     *
     * @return string
     */
    public function password()
    {
        return $this->plainPassword;
    }
}
