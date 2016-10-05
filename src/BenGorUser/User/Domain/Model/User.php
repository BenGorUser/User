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

namespace BenGorUser\User\Domain\Model;

use BenGorUser\User\Domain\Model\Event\UserEnabled;
use BenGorUser\User\Domain\Model\Event\UserInvited;
use BenGorUser\User\Domain\Model\Event\UserLoggedIn;
use BenGorUser\User\Domain\Model\Event\UserLoggedOut;
use BenGorUser\User\Domain\Model\Event\UserRegistered;
use BenGorUser\User\Domain\Model\Event\UserRememberPasswordRequested;
use BenGorUser\User\Domain\Model\Event\UserRoleGranted;
use BenGorUser\User\Domain\Model\Event\UserRoleRevoked;
use BenGorUser\User\Domain\Model\Exception\UserInactiveException;
use BenGorUser\User\Domain\Model\Exception\UserInvitationAlreadyAcceptedException;
use BenGorUser\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGorUser\User\Domain\Model\Exception\UserRoleAlreadyGrantedException;
use BenGorUser\User\Domain\Model\Exception\UserRoleAlreadyRevokedException;
use BenGorUser\User\Domain\Model\Exception\UserRoleInvalidException;

/**
 * User domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class User extends UserAggregateRoot
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
     * @var \DateTimeInterface
     */
    protected $createdOn;

    /**
     * The email.
     *
     * @var UserEmail
     */
    protected $email;

    /**
     * The invitation token.
     *
     * @var UserToken
     */
    protected $invitationToken;

    /**
     * The last login.
     *
     * @var \DateTimeInterface|null
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
     * @var \DateTimeInterface
     */
    protected $updatedOn;

    /**
     * Constructor.
     *
     * @param UserId            $anId      The id
     * @param UserEmail         $anEmail   The email
     * @param array             $userRoles Array which contains the roles
     * @param UserPassword|null $aPassword The encoded password
     */
    protected function __construct(
        UserId $anId,
        UserEmail $anEmail,
        array $userRoles,
        UserPassword $aPassword = null
    ) {
        $this->id = $anId;
        $this->email = $anEmail;
        $this->password = $aPassword;
        $this->createdOn = new \DateTimeImmutable();
        $this->updatedOn = new \DateTimeImmutable();

        $this->roles = [];
        foreach ($userRoles as $userRole) {
            $this->grant($userRole);
        }
    }

    /**
     * Sign up user.
     *
     * @param UserId       $anId      The id
     * @param UserEmail    $anEmail   The email
     * @param UserPassword $aPassword The encoded password
     * @param array        $userRoles Array which contains the roles
     *
     * @return static
     */
    public static function signUp(UserId $anId, UserEmail $anEmail, UserPassword $aPassword, array $userRoles)
    {
        $user = new static($anId, $anEmail, $userRoles, $aPassword);
        $user->confirmationToken = new UserToken();
        $user->publish(
            new UserRegistered(
                $user->id(),
                $user->email(),
                $user->confirmationToken()
            )
        );

        return $user;
    }

    /**
     * Invites user.
     *
     * @param UserId    $anId      The id
     * @param UserEmail $anEmail   The email
     * @param array     $userRoles Array which contains the roles
     *
     * @return static
     */
    public static function invite(UserId $anId, UserEmail $anEmail, array $userRoles)
    {
        $user = new static($anId, $anEmail, $userRoles);
        $user->invitationToken = new UserToken();
        $user->publish(
            new UserInvited(
                $user->id(),
                $user->email(),
                $user->invitationToken()
            )
        );

        return $user;
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
     * Accepts the invitation request.
     */
    public function acceptInvitation()
    {
        $this->invitationToken = null;
        $this->updatedOn = new \DateTimeImmutable();
        $this->publish(
            new UserRegistered(
                $this->id(),
                $this->email()
            )
        );
    }

    /**
     * Updates the user password.
     *
     * @param UserPassword $aPassword The old password
     */
    public function changePassword(UserPassword $aPassword)
    {
        $this->password = $aPassword;
        $this->rememberPasswordToken = null;
        $this->updatedOn = new \DateTimeImmutable();
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
     * @return \DateTimeInterface
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
        $this->updatedOn = new \DateTimeImmutable();

        $this->publish(
            new UserEnabled(
                $this->id,
                $this->email
            )
        );
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
        $this->updatedOn = new \DateTimeImmutable();

        $this->publish(
            new UserRoleGranted(
                $this->id,
                $this->email,
                $aRole
            )
        );
    }

    /**
     * Gets the invitation token.
     *
     * @return UserToken
     */
    public function invitationToken()
    {
        return $this->invitationToken;
    }

    /**
     * Checks if the user is enabled or not.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return null === $this->confirmationToken || null === $this->confirmationToken->token();
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
        return in_array($aRole->role(), static::availableRoles(), true);
    }

    /**
     * Gets the last login.
     *
     * @return \DateTimeInterface
     */
    public function lastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Validates user login for the given password.
     *
     * @param string              $aPlainPassword Plain password used to log in
     * @param UserPasswordEncoder $anEncoder      The encoder used to encode the password
     *
     * @throws UserInactiveException        when the user is not enabled
     * @throws UserPasswordInvalidException when the user password is invalid
     */
    public function login($aPlainPassword, UserPasswordEncoder $anEncoder)
    {
        if (false === $this->isEnabled()) {
            throw new UserInactiveException();
        }
        if (false === $this->password()->equals($aPlainPassword, $anEncoder)) {
            throw new UserPasswordInvalidException();
        }
        $this->lastLogin = new \DateTimeImmutable();

        $this->publish(
            new UserLoggedIn(
                $this->id,
                $this->email
            )
        );
    }

    /**
     * Updated the user state after logout.
     *
     * @throws UserInactiveException when the user is not enabled
     */
    public function logout()
    {
        if (false === $this->isEnabled()) {
            throw new UserInactiveException();
        }

        $this->publish(
            new UserLoggedOut(
                $this->id,
                $this->email
            )
        );
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
     * Updates the invitation token in case a user has
     * been already invited and has lost the token.
     *
     * @throws UserInvitationAlreadyAcceptedException in case user has already accepted the invitation
     */
    public function regenerateInvitationToken()
    {
        if (null === $this->invitationToken) {
            throw new UserInvitationAlreadyAcceptedException();
        }
        $this->invitationToken = new UserToken();
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

        $this->publish(
            new UserRememberPasswordRequested(
                $this->id,
                $this->email,
                $this->rememberPasswordToken
            )
        );
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
                $this->roles = array_values($this->roles);
                break;
            }
            throw new UserRoleAlreadyRevokedException();
        }
        $this->updatedOn = new \DateTimeImmutable();
        $this->publish(
            new UserRoleRevoked(
                $this->id,
                $this->email,
                $aRole
            )
        );
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
     * @return \DateTimeInterface
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
    public static function availableRoles()
    {
        return ['ROLE_USER', 'ROLE_ADMIN'];
    }
}
