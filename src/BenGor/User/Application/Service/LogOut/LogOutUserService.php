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

namespace BenGor\User\Application\Service\LogOut;

use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\Exception\UserInactiveException;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use Ddd\Application\Service\ApplicationService;

/**
 * User logout service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogOutUserService implements ApplicationService
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
     * @param LogOutUserRequest $request The request
     *
     * @throws UserDoesNotExistException when the user does not exist
     * @throws UserInactiveException     when the user is not enabled
     */
    public function execute($request = null)
    {
        $userId = $request->id();

        $user = $this->repository->userOfId(new UserId($userId));
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
