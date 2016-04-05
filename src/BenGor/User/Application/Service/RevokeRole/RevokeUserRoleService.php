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

use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use Ddd\Application\Service\ApplicationService;

/**
 * Revoke user role service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RevokeUserRoleService implements ApplicationService
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
     * @param RevokeUserRoleRequest $request The request
     *
     * @throws UserDoesNotExistException when the user does not exist
     */
    public function execute($request = null)
    {
        $id = $request->id();
        $role = $request->role();

        $user = $this->repository->userOfId(new UserId($id));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        $user->revoke(new UserRole($role));

        $this->repository->persist($user);
    }
}
