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
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Without old password change user password command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class WithoutOldPasswordChangeUserPasswordHandler
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
     * @param WithoutOldPasswordChangeUserPasswordCommand $aCommand The command
     *
     * @throws UserDoesNotExistException when the user does not exist
     */
    public function __invoke(WithoutOldPasswordChangeUserPasswordCommand $aCommand)
    {
        $user = $this->repository->userOfEmail(new UserEmail($aCommand->email()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        $user->changePassword(UserPassword::fromPlain($aCommand->newPlainPassword(), $this->encoder));

        $this->repository->persist($user);
    }
}
