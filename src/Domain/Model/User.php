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

use BenGor\User\Domain\Model\Event\UserEnabled;
use BenGor\User\Domain\Model\Event\UserLoggedIn;
use BenGor\User\Domain\Model\Event\UserLoggedOut;
use BenGor\User\Domain\Model\Event\UserRegistered;
use BenGor\User\Domain\Model\Event\UserRememberPasswordRequested;
use Ddd\Domain\DomainEventPublisher;

/**
 * User domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class User
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
     * @param UserId                $anId               The id
     * @param UserEmail             $anEmail            The email
     * @param string                $aPassword          The plain password
     * @param UserPasswordEncoder   $anEncoder          The password encoder
     * @param UserConfirmationToken $aConfirmationToken The confirmation token
     * @param \DateTime             $aCreatedOn         The created on
     * @param \DateTime             $anUpdatedOn        The updated on
     * @param \DateTime|null        $aLastLogin         The last login
     */
    private function __construct(
        UserId $anId,
        UserEmail $anEmail,
        $aPassword,
        UserPasswordEncoder $anEncoder = null,
        UserConfirmationToken $aConfirmationToken = null,
        \DateTime $aCreatedOn = null,
        \DateTime $anUpdatedOn = null,
        \DateTime $aLastLogin = null
    ) {
        $this->id = $anId;
        $this->email = $anEmail;
        $this->password = new UserPassword($aPassword, $anEncoder);
        $this->createdOn = $aCreatedOn ?: new \DateTime();
        $this->updatedOn = $anUpdatedOn ?: new \DateTime();
        $this->lastLogin = $aLastLogin ?: null;

        if (true === $aConfirmationToken) {
            $this->confirmationToken = new UserConfirmationToken();
        } else {
            $this->confirmationToken = $aConfirmationToken;
        }

        DomainEventPublisher::instance()->publish(new UserRegistered($this));
    }

    /**
     * Named register static constructor.
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
        return new self($anId, $anEmail, $aPassword, $encoder, true);
    }

    /**
     * Named build static constructor.
     *
     * This method is required to transform a plain
     * database properties into domain user object.
     *
     * @param UserId                     $anId               The id
     * @param UserEmail                  $anEmail            The email
     * @param string                     $aPassword          The plain password
     * @param \DateTime                  $aCreatedOn         The created on
     * @param \DateTime                  $anUpdatedOn        The updated on
     * @param \DateTime|null             $aLastLogin         The last login
     * @param UserConfirmationToken|null $aConfirmationToken The confirmation token
     *
     * @return User
     */
    public static function build(
        UserId $anId,
        UserEmail $anEmail,
        $aPassword,
        \DateTime $aCreatedOn,
        \DateTime $anUpdatedOn,
        \DateTime $aLastLogin = null,
        UserConfirmationToken $aConfirmationToken = null
    ) {
        return new self($anId, $anEmail, $aPassword, null, $aConfirmationToken, $aCreatedOn, $anUpdatedOn, $aLastLogin);
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
     * Gets the confirmation token.
     *
     * @return UserConfirmationToken
     */
    public function confirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Gets the created on.
     *
     * @return \DateTime
     */
    public function createdOn()
    {
        return $this->createdOn;
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
     * Enables the user account.
     */
    public function enableAccount()
    {
        $this->confirmationToken = null;

        DomainEventPublisher::instance()->publish(new UserEnabled($this));
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
     * Gets the last login.
     *
     * @return \DateTime
     */
    public function lastLogin()
    {
        return $this->lastLogin;
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
     * Updated the user state after logout.
     */
    public function logout()
    {
        DomainEventPublisher::instance()->publish(new UserLoggedOut($this));
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

    /**
     * Gets the updated on.
     *
     * @return \DateTime
     */
    public function updatedOn()
    {
        return $this->updatedOn;
    }
}
