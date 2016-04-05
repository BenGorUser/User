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

namespace BenGor\User\Application\Service\GrantRole;

use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use Ddd\Application\Service\ApplicationService;

/**
 * Grant user role service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GrantUserRoleService implements ApplicationService
{
    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param UserRepository $aRepository The user repository
     */
    public function __construct(UserRepository $aRepository)
    {
        $this->repository = $aRepository;
    }

    /**
     * Executes application service.
     *
     * @param GrantUserRoleRequest $request The request
     *
     * @throws UserDoesNotExistException when user does not exist
     */
    public function execute($request = null)
    {
        $id = $request->id();
        $role = $request->role();

        $user = $this->repository->userOfId(new UserId($id));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        $user->grant(new UserRole($role));

        $this->repository->persist($user);
    }
}
