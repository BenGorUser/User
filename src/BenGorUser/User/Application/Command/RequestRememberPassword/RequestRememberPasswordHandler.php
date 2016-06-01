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

namespace BenGorUser\User\Application\Command\RequestRememberPassword;

use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Request remember password command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RequestRememberPasswordHandler
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
     * @param RequestRememberPasswordCommand $aCommand The command
     *
     * @throws UserDoesNotExistException when the user does not exist
     */
    public function __invoke(RequestRememberPasswordCommand $aCommand)
    {
        $user = $this->repository->userOfEmail(
            new UserEmail($aCommand->email())
        );
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        $user->rememberPassword();

        $this->repository->persist($user);
    }
}
