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

namespace BenGor\User\Application\Service\SignUp;

/**
 * Sign up user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SignUpUserRequest
{
    /**
     * The user email.
     *
     * @var string
     */
    private $email;

    /**
     * The invitation token.
     *
     * @var string
     */
    private $invitationToken;

    /**
     * The plain password.
     *
     * @var string
     */
    private $plainPassword;

    /**
     * Array which contains the roles.
     *
     * @var array
     */
    private $roles;

    /**
     * Named constructor from invitation.
     *
     * @param string $anInvitationToken The invitation token
     * @param string $aPlainPassword    The plain password
     * @param array  $roles             Array which contains the roles
     *
     * @return self
     */
    public static function fromInvitationToken($anInvitationToken, $aPlainPassword, array $roles)
    {
        return new self($aPlainPassword, $roles, null, $anInvitationToken);
    }

    /**
     * Named constructor from email.
     *
     * @param string $anEmail        The email
     * @param string $aPlainPassword The plain password
     * @param array  $roles          Array which contains the roles
     *
     * @return self
     */
    public static function fromEmail($anEmail, $aPlainPassword, array $roles)
    {
        return new self($aPlainPassword, $roles, $anEmail);
    }

    /**
     * Private constructor.
     *
     * @param string      $aPlainPassword    The plain password
     * @param array       $roles             Array which contains the roles
     * @param string|null $anEmail           The email
     * @param string|null $anInvitationToken The invitation token
     */
    private function __construct($aPlainPassword, array $roles, $anEmail = null, $anInvitationToken = null)
    {
        $this->email = $anEmail;
        $this->invitationToken = $anInvitationToken;
        $this->plainPassword = $aPlainPassword;
        $this->roles = $roles;
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
     * Gets the invitation token.
     *
     * @return string
     */
    public function invitationToken()
    {
        return $this->invitationToken;
    }

    /**
     * Gets the user plain password.
     *
     * @return string
     */
    public function password()
    {
        return $this->plainPassword;
    }

    /**
     * Gets the roles.
     *
     * @return array
     */
    public function roles()
    {
        return $this->roles;
    }
}
