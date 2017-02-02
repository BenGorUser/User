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

namespace BenGorUser\User\Application\Command\PurgeOutdatedTokens;

use BenGorUser\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Purge outdated remember password tokens command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class PurgeOutdatedRememberPasswordTokensUserHandler
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
     * @param PurgeOutdatedRememberPasswordTokensUserCommand $aCommand The command
     */
    public function __invoke(PurgeOutdatedRememberPasswordTokensUserCommand $aCommand)
    {
        $users = $this->repository->all();
        foreach ($users as $user) {
            try {
                $user->cleanRememberPasswordToken();
            } catch (UserTokenNotFoundException $exception) {
                continue;
            }
            $this->repository->persist($user);
        }
    }
}
