<?php

namespace BenGor\User\Domain\Model;

/**
 * User password domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserPassword
{
    /**
     * The encoded password.
     *
     * @var string
     */
    private $encodedPassword;

    /**
     * The salt.
     *
     * @var string
     */
    private $salt;

    /**
     * Constructor.
     *
     * @param string              $aPlainPassword The plain password
     * @param UserPasswordEncoder $anEncoder      The encoder
     * @param string|null         $salt           The salt
     */
    public function __construct($aPlainPassword, UserPasswordEncoder $anEncoder, $salt = null)
    {
        $this->salt = $salt ?: base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->encodedPassword = $anEncoder->encode($aPlainPassword, $this->salt);
    }

    /**
     * Method that checks if the password given is equal to the current.
     *
     * @param UserPassword $aUserPassword A user password
     *
     * @return bool
     */
    public function equals(UserPassword $aUserPassword)
    {
        return $this->encodedPassword === $aUserPassword->encodedPassword;
    }

    /**
     * Gets the encoded password.
     *
     * @return string
     */
    public function encodedPassword()
    {
        return $this->encodedPassword;
    }

    /**
     * Gets the salt.
     *
     * @return string
     */
    public function salt()
    {
        return $this->salt;
    }

    /**
     * Magic method that represents the password in string format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->encodedPassword;
    }
}
