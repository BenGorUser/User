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

namespace BenGor\User\Application\Service\RevokeRole;

/**
 * Revoke user role request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RevokeUserRoleRequest
{
    /**
     * The user id.
     *
     * @var string
     */
    private $id;

    /**
     * The role.
     *
     * @var string
     */
    private $role;

    /**
     * Constructor.
     *
     * @param string $anId  The user id
     * @param string $aRole The role
     */
    public function __construct($anId, $aRole)
    {
        $this->id = $anId;
        $this->role = $aRole;
    }

    /**
     * Gets the user id.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Gets the role.
     *
     * @return string
     */
    public function role()
    {
        return $this->role;
    }
}
