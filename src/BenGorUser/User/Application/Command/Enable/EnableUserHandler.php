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

namespace BenGorUser\User\Application\Command\Enable;

use BenGorUser\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;

/**
 * Enable user command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class EnableUserHandler
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
     * @param EnableUserCommand $aCommand The command
     *
     * @throws UserTokenNotFoundException when the user token does not exist
     */
    public function __invoke(EnableUserCommand $aCommand)
    {
        $confirmationToken = $aCommand->confirmationToken();

        $user = $this->repository->userOfConfirmationToken(new UserToken($confirmationToken));
        if (null === $user) {
            throw new UserTokenNotFoundException();
        }
        $user->enableAccount();

        $this->repository->persist($user);
    }
}
