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

namespace BenGor\User\Application\Service\RequestRememberPassword;

use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserRepository;

/**
 * Request remember password service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RequestRememberPasswordService
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
     * @param RequestRememberPasswordRequest $request The request
     *
     * @throws UserDoesNotExistException when the user does not exist
     */
    public function execute(RequestRememberPasswordRequest $request)
    {
        $email = $request->email();

        $user = $this->repository->userOfEmail(new UserEmail($email));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $user->rememberPassword();
        $this->repository->persist($user);
    }
}
