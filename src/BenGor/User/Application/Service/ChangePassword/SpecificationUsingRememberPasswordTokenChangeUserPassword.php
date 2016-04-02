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

namespace BenGor\User\Application\Service\ChangePassword;

use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserToken;

/**
 * Using remember password token specification of change user password service.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SpecificationUsingRememberPasswordTokenChangeUserPassword implements SpecificationChangeUserPassword
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
    public function user(ChangeUserPasswordRequest $request)
    {
        $user = $this->repository->userOfRememberPasswordToken(new UserToken($request->rememberPasswordToken()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        return $user;
    }
}
