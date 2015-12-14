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

namespace BenGor\User\Domain\Model;

use BenGor\User\Domain\Model\Event\UserEnabled;
use BenGor\User\Domain\Model\Event\UserLoggedIn;
use BenGor\User\Domain\Model\Event\UserLoggedOut;
use BenGor\User\Domain\Model\Event\UserRegistered;
use BenGor\User\Domain\Model\Event\UserRememberPasswordRequested;
use BenGor\User\Domain\Model\Event\UserRoleGranted;
use BenGor\User\Domain\Model\Event\UserRoleRevoked;
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\Exception\UserRoleAlreadyGrantedException;
use BenGor\User\Domain\Model\Exception\UserRoleAlreadyRevokedException;
use BenGor\User\Domain\Model\Exception\UserRoleInvalidException;
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
    protected $id;

    /**
     * The confirmation token.
     *
     * @var UserToken
     */
    protected $confirmationToken;

    /**
     * Created on.
     *
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * The email.
     *
     * @var UserEmail
     */
    protected $email;

    /**
     * The last login.
     *
     * @var \DateTime|null
     */
    protected $lastLogin;

    /**
     * The password.
     *
     * @var UserPassword
     */
    protected $password;

    /**
     * The remember password token.
     *
     * @var UserToken
     */
    protected $rememberPasswordToken;

    /**
     * Array which contains roles.
     *
     * @var UserRole[]
     */
    protected $roles;

    /**
     * Updated on.
     *
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * Constructor.
     *
     * @param UserId         $anId                   The id
     * @param UserEmail      $anEmail                The email
     * @param UserPassword   $aPassword              The encoded password
     * @param array          $userRoles              Array which contains the roles
     * @param \DateTime|null $aCreatedOn             The created on
     * @param \DateTime|null $anUpdatedOn            The updated on
     * @param \DateTime|null $aLastLogin             The last login
     * @param UserToken|null $aConfirmationToken     The confirmation token
     * @param UserToken|null $aRememberPasswordToken The remember me token
     */
    public function __construct(
        UserId $anId,
        UserEmail $anEmail,
        UserPassword $aPassword,
        array $userRoles,
        \DateTime $aCreatedOn = null,
        \DateTime $anUpdatedOn = null,
        \DateTime $aLastLogin = null,
        UserToken $aConfirmationToken = null,
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

        $this->roles = [];
        foreach ($userRoles as $userRole) {
            $this->grant($userRole);
        }

        DomainEventPublisher::instance()->publish(new UserRegistered($this));
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
            throw new UserPasswordInvalidException();
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
     * Adds the given role.
     *
     * @param UserRole $aRole The user role
     */
    public function grant(UserRole $aRole)
    {
        if (false === $this->isRoleAllowed($aRole)) {
            throw new UserRoleInvalidException();
        }
        if (true === $this->isGranted($aRole)) {
            throw new UserRoleAlreadyGrantedException();
        }
        $this->roles[] = $aRole;

        DomainEventPublisher::instance()->publish(new UserRoleGranted($this));
    }

    /**
     * Checks if the user is enabled or not.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return null === $this->confirmationToken;
    }

    /**
     * Checks if the user has the given role.
     *
     * @param UserRole $aRole The user role
     *
     * @return bool
     */
    public function isGranted(UserRole $aRole)
    {
        foreach ($this->roles as $role) {
            if ($role->equals($aRole)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the role given appears between allowed roles.
     *
     * @param UserRole $aRole The user role
     *
     * @return bool
     */
    public function isRoleAllowed(UserRole $aRole)
    {
        return in_array($aRole->role(), $this->availableRoles(), true);
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
     * Removes the given role.
     *
     * @param UserRole $aRole The user role
     */
    public function revoke(UserRole $aRole)
    {
        if (false === $this->isRoleAllowed($aRole)) {
            throw new UserRoleInvalidException();
        }
        foreach ($this->roles as $key => $role) {
            if ($role->equals($aRole)) {
                unset($this->roles[$key]);
                break;
            }
            throw new UserRoleAlreadyRevokedException();
        }
        DomainEventPublisher::instance()->publish(new UserRoleRevoked($this));
    }

    /**
     * Gets the roles.
     *
     * @return UserRole[]
     */
    public function roles()
    {
        return $this->roles;
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

    /**
     * Gets the available roles in scalar type.
     *
     * This method is an extension point that it allows
     * to add more roles easily in the domain.
     *
     * @return array
     */
    protected function availableRoles()
    {
        return ['ROLE_USER', 'ROLE_ADMIN'];
    }
}
