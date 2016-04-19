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

/**
 * Change user password request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordRequest
{
    /**
     * The user email.
     *
     * @var string
     */
    private $email;

    /**
     * The user id.
     *
     * @var string
     */
    private $id;

    /**
     * The new plain password.
     *
     * @var string
     */
    private $newPlainPassword;

    /**
     * The old plain password.
     *
     * @var string
     */
    private $oldPlainPassword;

    /**
     * The password remember token.
     *
     * @var string
     */
    private $rememberPasswordToken;

    /**
     * Named constructor from user id.
     *
     * @param string $anId               The user id
     * @param string $aNewPlainPassword  The new plain password
     * @param string $anOldPlainPassword The old plain password
     *
     * @return self
     */
    public static function from($anId, $aNewPlainPassword, $anOldPlainPassword)
    {
        return new self($aNewPlainPassword, $anOldPlainPassword, $anId);
    }

    /**
     * Named constructor from user email and without old password.
     *
     * @param string $anEmail        The user email
     * @param string $aPlainPassword The new plain password
     *
     * @return self
     */
    public static function fromEmail($anEmail, $aPlainPassword)
    {
        return new self($aPlainPassword, null, null, null, $anEmail);
    }

    /**
     * Named constructor from remember password token.
     *
     * @param string $aNewPlainPassword      The new plain password
     * @param string $aRememberPasswordToken The password remember token
     *
     * @return self
     */
    public static function fromRememberPasswordToken($aNewPlainPassword, $aRememberPasswordToken)
    {
        return new self($aNewPlainPassword, null, null, $aRememberPasswordToken);
    }

    /**
     * Constructor.
     *
     * @param string      $aNewPlainPassword      The new plain password
     * @param string      $anOldPlainPassword     The old plain password
     * @param string      $anId                   The user id
     * @param string|null $aRememberPasswordToken The remember password token
     * @param string|null $anEmail                The user email
     */
    private function __construct(
        $aNewPlainPassword,
        $anOldPlainPassword = null,
        $anId = null,
        $aRememberPasswordToken = null,
        $anEmail = null
    ) {
        $this->email = $anEmail;
        $this->id = $anId;
        $this->newPlainPassword = $aNewPlainPassword;
        $this->oldPlainPassword = $anOldPlainPassword;
        $this->rememberPasswordToken = $aRememberPasswordToken;
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
     * Gets the user id.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
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
     * Gets the old plain password.
     *
     * @return string
     */
    public function oldPlainPassword()
    {
        return $this->oldPlainPassword;
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
