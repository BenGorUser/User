<?php

namespace BenGor\User\Domain\Model;

use Ddd\Domain\DomainEventPublisher;

/**
 * User domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class User
{
    /**
     * The id.
     *
     * @var UserId
     */
    private $id;

    /**
     * The confirmation token.
     *
     * @var UserConfirmationToken
     */
    private $confirmationToken;

    /**
     * Created on.
     *
     * @var \DateTime
     */
    private $createdOn;

    /**
     * The email.
     *
     * @var UserEmail
     */
    private $email;

    /**
     * The last login.
     *
     * @var \DateTime|null
     */
    private $lastLogin;

    /**
     * The password.
     *
     * @var UserPassword
     */
    private $password;

    /**
     * Updated on.
     *
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * Constructor.
     *
     * @param UserId              $anId      The id
     * @param UserEmail           $anEmail   The email
     * @param string              $aPassword The plain password
     * @param UserPasswordEncoder $encoder   The password encoder
     */
    private function __construct(UserId $anId, UserEmail $anEmail, $aPassword, UserPasswordEncoder $encoder)
    {
        $this->id = $anId;
        $this->email = $anEmail;
        $this->password = new UserPassword($aPassword, $encoder);
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
        $this->lastLogin = null;
        $this->confirmationToken = new UserConfirmationToken();

        DomainEventPublisher::instance()->publish(new UserRegistered($this));
    }

    /**
     * Named static constructor.
     *
     * @param UserId              $anId      The id
     * @param UserEmail           $anEmail   The email
     * @param string              $aPassword The plain password
     * @param UserPasswordEncoder $encoder   The password encoder
     *
     * @return self
     */
    public static function register(UserId $anId, UserEmail $anEmail, $aPassword, UserPasswordEncoder $encoder)
    {
        return new self($anId, $anEmail, $aPassword, $encoder);
    }

    /**
     * Gets the id.
     *
     * @return UserId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Enables the user account.
     */
    public function enableAccount()
    {
        $this->confirmationToken = null;

        DomainEventPublisher::instance()->publish(new UserEnabled($this));
    }

    /**
     * Gets the confirmation token.
     *
     * @return UserConfirmationToken
     */
    public function confirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Gets the email.
     *
     * @return UserEmail
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Checks if the user is enabled or not.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->confirmationToken === null;
    }

    /**
     * Updated the user state after login.
     */
    public function login()
    {
        $this->lastLogin = new \DateTime();

        DomainEventPublisher::instance()->publish(new UserLoggedIn($this));
    }

    /**
     * Gets the password.
     *
     * @return UserPassword
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * Remembers the password.
     */
    public function rememberPassword()
    {
        $this->confirmationToken = new UserConfirmationToken();

        DomainEventPublisher::instance()->publish(new UserRememberPasswordRequested($this));
    }
}
