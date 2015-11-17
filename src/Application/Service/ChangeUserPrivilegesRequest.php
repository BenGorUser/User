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

namespace BenGor\User\Application\Service;

/**
 * Change user privileges request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class ChangeUserPrivilegesRequest
{
    /**
     * The user id.
     *
     * @var string
     */
    private $id;

    /**
     * Array which contains the roles.
     *
     * @var array
     */
    private $roles;

    /**
     * Constructor.
     *
     * @param string $anId  The user id
     * @param array  $roles Array which contains the roles
     */
    public function __construct($anId, $roles)
    {
        $this->id = $anId;
        $this->roles = $roles;
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
     * Gets the roles.
     *
     * @return array
     */
    public function roles()
    {
        return $this->roles;
    }
}
