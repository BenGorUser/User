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

/**
 * Role entity class that decorates the UserRole domain class.
 *
 * It is required to resolve the relationship
 * between User and UserRole domain objects.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class Role
{
    /**
     * The role id.
     *
     * @var RoleId
     */
    private $id;

    /**
     * The role.
     *
     * @var UserRole
     */
    private $role;

    /**
     * Constructor.
     *
     * @param RoleId   $anId  The id
     * @param UserRole $aRole The role domain object
     */
    public function __construct(RoleId $anId, UserRole $aRole)
    {
        $this->id = $anId;
        $this->role = $aRole;
    }

    /**
     * Gets the id.
     *
     * @return RoleId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Gets the role.
     *
     * @return UserRole
     */
    public function role()
    {
        return $this->role;
    }
}
