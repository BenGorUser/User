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
     * @param string      $anEncodedPassword The encoded password
     * @param string|null $salt              The salt
     */
    private function __construct($anEncodedPassword, $salt)
    {
        $this->salt = $salt;
        $this->encodedPassword = $anEncodedPassword;
    }

    /**
     * Named static constructor with the given encoded password.
     *
     * @param string $anEncodedPassword The encoded password
     * @param string $salt              The salt
     *
     * @return self
     */
    public static function fromEncoded($anEncodedPassword, $salt)
    {
        return new self($anEncodedPassword, $salt);
    }

    /**
     * Named static constructor with the given plain password and encoder.
     *
     * @param string              $aPlainPassword The plain password
     * @param UserPasswordEncoder $anEncoder      The encoder
     * @param string|null         $salt           The salt
     *
     * @return self
     */
    public static function fromPlain($aPlainPassword, UserPasswordEncoder $anEncoder, $salt = null)
    {
        if (null === $salt) {
            $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        }
        $encodedPassword = $anEncoder->encode($aPlainPassword, $salt);

        return new self($encodedPassword, $salt);
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
