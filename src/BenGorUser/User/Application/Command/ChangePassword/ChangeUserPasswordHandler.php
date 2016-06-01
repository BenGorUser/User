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

namespace BenGorUser\User\Application\Command\ChangePassword;

use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Change user password command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordHandler
{
    /**
     * The user password encoder.
     *
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $repository;

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
     * Handles the given command.
     *
     * @param ChangeUserPasswordCommand $aCommand The command
     *
     * @throws UserDoesNotExistException    when the user does not exist
     * @throws UserPasswordInvalidException when the user password is invalid
     */
    public function __invoke(ChangeUserPasswordCommand $aCommand)
    {
        $user = $this->repository->userOfId(new UserId($aCommand->id()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        if (false === $user->password()->equals($aCommand->oldPlainPassword(), $this->encoder)) {
            throw new UserPasswordInvalidException();
        }
        $user->changePassword(UserPassword::fromPlain($aCommand->newPlainPassword(), $this->encoder));

        $this->repository->persist($user);
    }
}
