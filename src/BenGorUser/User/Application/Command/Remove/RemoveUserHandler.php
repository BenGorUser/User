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

namespace BenGorUser\User\Application\Command\Remove;

use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Remove user command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RemoveUserHandler
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
     * Handles the given command.
     *
     * @param RemoveUserCommand $aCommand The command
     *
     * @throws UserDoesNotExistException when the user does not exist
     */
    public function __invoke(RemoveUserCommand $aCommand)
    {
        $user = $this->repository->userOfId(new UserId($aCommand->id()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $this->repository->remove($user);
    }
}
