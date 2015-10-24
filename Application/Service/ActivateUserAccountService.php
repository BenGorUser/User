<?php

namespace BenGor\User\Application\Service;

use BenGor\User\Domain\Model\UserConfirmationToken;
use BenGor\User\Domain\Model\UserConfirmationTokenNotFoundException;
use BenGor\User\Repository\UserRepository;

/**
 * Sign up user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ActivateUserAccountService implements ApplicationService
{
    /**
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

        $user = $this->repository->userOfConfirmationToken(
            new UserConfirmationToken($confirmationToken)
        );

        if (null === $user) {
            throw new UserConfirmationTokenNotFoundException();
        }

        $user->enableAccount();

        $this->repository->persist();
    }
}
