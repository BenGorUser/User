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
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;

/**
 * Specification default change user password service.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DefaultChangeUserPasswordSpecification implements ChangeUserPasswordSpecification
{
    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * The user password encoder.
     *
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * Constructor.
     *
     * @param UserRepository      $aRepository The user repository
     * @param UserPasswordEncoder $anEncoder   The password encoder
     */
    public function __construct(UserRepository $aRepository, UserPasswordEncoder $anEncoder)
    {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function user(ChangeUserPasswordRequest $request)
    {
        $user = $this->repository->userOfId(new UserId($request->id()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        if (false === $user->password()->equals($request->oldPlainPassword(), $this->encoder)) {
            throw new UserPasswordInvalidException();
        }

        return $user;
    }
}
