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

use BenGor\User\Domain\Model\Exception\UserInvalidRoleException;

/**
 * User role domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
abstract class UserRole
{
    /**
     * The role in plain string.
     *
     * @var string
     */
    private $role;

    /**
     * Constructor.
     *
     * @param string $aRole A role in primitive string
     *
     * @throws UserInvalidRoleException when the role is not valid
     */
    public function __construct($aRole)
    {
        if (!in_array($aRole, $this->roles())) {
            throw new UserInvalidRoleException();
        }
        $this->role = $aRole;
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

    /**
     * Method that checks if the user role given is equal to the current.
     *
     * @param UserRole $aRole The user role
     *
     * @return bool
     */
    public function equals(UserRole $aRole)
    {
        return strtolower($this->role) === strtolower($aRole->role());
    }

    /**
     * Magic method that represents the user role in string format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->role;
    }

    /**
     * Contains the different roles that the domain supports.
     *
     * @return array
     */
    abstract protected function roles();
}
