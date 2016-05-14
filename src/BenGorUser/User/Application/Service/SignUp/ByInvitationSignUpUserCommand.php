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

namespace BenGorUser\User\Application\Service\SignUp;

/**
 * By invitation sign up user command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationSignUpUserCommand
{
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
     * Constructor.
     *
     * @param string $anInvitationToken The invitation token
     * @param string $aPlainPassword    The plain password
     * @param array  $roles             Array which contains the roles
     */
    public function __construct($anInvitationToken, $aPlainPassword, array $roles)
    {
        $this->invitationToken = $anInvitationToken;
        $this->plainPassword = $aPlainPassword;
        $this->roles = $roles;
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
