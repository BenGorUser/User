<?php

namespace BenGor\User\Domain\Model;

/**
 * User email domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserEmail
{
    /**
     * The email in plain string.
     *
     * @var string
     */
    private $email;

    /**
     * The domain of email, for example: "gmail.com"
     *
     * @var string
     */
    private $domain;

    /**
     * The part that exists before the @.
     *
     * @var string
     */
    private $localPart;

    /**
     * Constructor.
     *
     * @param string $anEmail An email in primitive string
     *
     * @throws UserInvalidEmailException when the email is not valid
     */
    public function __construct($anEmail)
    {
        if (!filter_var($anEmail, FILTER_VALIDATE_EMAIL)) {
            throw new UserInvalidEmailException();
        }
        $this->email = $anEmail;
        $this->localPart = implode(explode('@', $this->email, -1), '@');
        $this->domain = str_replace($this->localPart . '@', '', $this->email);
    }

    /**
     * Gets the email.
     *
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Gets the local part of email address, for example: "info" into "info@kreta.com".
     *
     * @return string
     */
    public function localPart()
    {
        return $this->localPart;
    }

    /**
     * Gets the domain of email address, for example: "gmail.com"
     *
     * @return string
     */
    public function domain()
    {
        return $this->domain;
    }

    /**
     * Method that checks if the email given is equal to the current.
     *
     * @param UserEmail $anEmail The email
     *
     * @return bool
     */
    public function equals(UserEmail $anEmail)
    {
        return strtolower((string)$this) === strtolower((string)$anEmail);
    }

    /**
     * Magic method that represents the user email in string format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->email;
    }
}

