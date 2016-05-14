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

/**
 * Without old password change user password command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class WithoutOldPasswordChangeUserPasswordCommand
{
    /**
     * The user email.
     *
     * @var string
     */
    private $email;

    /**
     * The new plain password.
     *
     * @var string
     */
    private $newPlainPassword;

    /**
     * Constructor.
     *
     * @param string $anEmail           The user email
     * @param string $aNewPlainPassword The new plain password
     */
    public function __construct($anEmail, $aNewPlainPassword)
    {
        $this->email = $anEmail;
        $this->newPlainPassword = $aNewPlainPassword;
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
     * Gets the new plain password.
     *
     * @return string
     */
    public function newPlainPassword()
    {
        return $this->newPlainPassword;
    }
}
