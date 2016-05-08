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

namespace BenGorUser\User\Application\Service\ChangePassword;

use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Specification default change user password command handler.
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
    public function user(ChangeUserPasswordCommand $aCommand)
    {
        $user = $this->repository->userOfId(new UserId($aCommand->id()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        if (false === $user->password()->equals($aCommand->oldPlainPassword(), $this->encoder)) {
            throw new UserPasswordInvalidException();
        }

        return $user;
    }
}
