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

use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use Ddd\Application\Service\ApplicationService;

/**
 * Change user privileges service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class ChangeUserPrivilegesService implements ApplicationService
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
     * {@inheritdoc}
     */
    public function execute($request = null)
    {
        $id = $request->id();
        $roles = $request->roles();

        $user = $this->repository->userOfId(new UserId($id));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, $roles);

        $user->setRoles($userRoles);

        $this->repository->persist($user);
    }
}
