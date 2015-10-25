<?php

/*
 * This file is part of the User library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\User\Application\Service;

use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\Exception\UserInactiveException;
use BenGor\User\Domain\Model\UserRepository;

/**
 * User logout service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class LogOutUserService
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
        $userId = $request->id();

        $user = $this->repository->userOfId($userId);
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        if (false === $user->isEnabled()) {
            throw new UserInactiveException();
        }
        $user->logout();

        $this->repository->persist($user);
    }
}
