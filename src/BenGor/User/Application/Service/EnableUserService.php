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

use BenGor\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserToken;
use Ddd\Application\Service\ApplicationService;

/**
 * Enable user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class EnableUserService implements ApplicationService
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
        $confirmationToken = $request->confirmationToken();

        $user = $this->repository->userOfConfirmationToken(new UserToken($confirmationToken));
        if (null === $user) {
            throw new UserTokenNotFoundException();
        }
        $user->enableAccount();

        $this->repository->persist($user);
    }
}
