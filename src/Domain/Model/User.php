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
use BenGor\User\Domain\Model\Exception\UserInvalidPasswordException;
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
     * @var UserToken
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
     * The remember password token.
     *
     * @var UserToken
     */
    private $rememberPasswordToken;

    /**
     * Updated on.
     *
     * @var \DateTime
     */
    private $updatedOn;

    /**
     * Constructor.
     *
     * @param UserId         $anId                   The id
     * @param UserEmail      $anEmail                The email
     * @param UserPassword   $aPassword              The plain password
     * @param UserToken      $aConfirmationToken     The confirmation token
     * @param \DateTime      $aCreatedOn             The created on
     * @param \DateTime      $anUpdatedOn            The updated on
     * @param \DateTime|null $aLastLogin             The last login
     * @param UserToken|null $aRememberPasswordToken The remember me token
     */
    protected function __construct(
        UserId $anId,
        UserEmail $anEmail,
        UserPassword $aPassword,
        UserToken $aConfirmationToken = null,
        \DateTime $aCreatedOn = null,
        \DateTime $anUpdatedOn = null,
        \DateTime $aLastLogin = null,
        UserToken $aRememberPasswordToken = null
    ) {
        $this->id = $anId;
        $this->email = $anEmail;
        $this->password = $aPassword;
        $this->confirmationToken = $aConfirmationToken ?: new UserToken();
        $this->createdOn = $aCreatedOn ?: new \DateTime();
        $this->updatedOn = $anUpdatedOn ?: new \DateTime();
        $this->lastLogin = $aLastLogin ?: null;
        $this->rememberPasswordToken = $aRememberPasswordToken;

        DomainEventPublisher::instance()->publish(new UserRegistered($this));
    }

    /**
     * Named register static constructor.
     *
     * @param UserId       $anId      The id
     * @param UserEmail    $anEmail   The email
     * @param UserPassword $aPassword The password
     *
     * @return self
     */
    public static function register(UserId $anId, UserEmail $anEmail, $aPassword)
    {
        return new self($anId, $anEmail, $aPassword);
    }

    /**
     * Named build static constructor.
     *
     * This method is required to transform a plain
     * database properties into domain user object.
     *
     * @param UserId         $anId                   The id
     * @param UserEmail      $anEmail                The email
     * @param UserPassword   $aPassword              The encoded password
     * @param \DateTime      $aCreatedOn             The created on
     * @param \DateTime      $anUpdatedOn            The updated on
     * @param \DateTime|null $aLastLogin             The last login
     * @param userToken|null $aConfirmationToken     The confirmation token
     * @param UserToken|null $aRememberPasswordToken The remember me token
     *
     * @return self
     */
    public static function build(
        UserId $anId,
        UserEmail $anEmail,
        UserPassword $aPassword,
        \DateTime $aCreatedOn,
        \DateTime $anUpdatedOn,
        \DateTime $aLastLogin = null,
        UserToken $aConfirmationToken = null,
        UserToken $aRememberPasswordToken = null
    ) {
        return new self(
            $anId,
            $anEmail,
            $aPassword,
            $aConfirmationToken,
            $aCreatedOn,
            $anUpdatedOn,
            $aLastLogin,
            $aRememberPasswordToken
        );
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
     * Updates the user password.
     *
     * @param UserPassword $anOldPassword The old password
     * @param UserPassword $aNewPassword  The new password
     */
    public function changePassword(UserPassword $anOldPassword, UserPassword $aNewPassword)
    {
        if (false === $this->password()->equals($anOldPassword)) {
            throw new UserInvalidPasswordException();
        }
        $this->password = $aNewPassword;
        $this->rememberPasswordToken = null;
    }

    /**
     * Gets the confirmation token.
     *
     * @return UserToken
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
     * Gets the remember password token.
     *
     * @return UserToken
     */
    public function rememberPasswordToken()
    {
        return $this->rememberPasswordToken;
    }

    /**
     * Remembers the password.
     */
    public function rememberPassword()
    {
        $this->rememberPasswordToken = new UserToken();

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
