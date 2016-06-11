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

namespace BenGorUser\User\Application\Command\Invite;

use Ramsey\Uuid\Uuid;

/**
 * User invite command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InviteUserCommand
{
    /**
     * The user id.
     *
     * @var string
     */
    private $id;

    /**
     * The user email.
     *
     * @var string
     */
    private $email;

    /**
     * Array which contains the roles.
     *
     * @var array
     */
    private $roles;

    /**
     * Constructor.
     *
     * @param string      $anEmail The user email
     * @param array       $roles   Array which contains the roles
     * @param string|null $anId    User id, it can be null
     */
    public function __construct($anEmail, array $roles, $anId = null)
    {
        $this->id = null === $anId ? Uuid::uuid4()->toString() : $anId;
        $this->email = $anEmail;
        $this->roles = $roles;
    }

    /**
     * Gets the id.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Gets the user email.
     *
     * @return string
     */
    public function email()
    {
        return $this->email;
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
