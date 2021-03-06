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

use BenGorUser\User\Domain\Model\Exception\UserTokenExpiredException;
use BenGorUser\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;

/**
 * By request remember password change user password command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByRequestRememberPasswordChangeUserPasswordHandler
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
     * @param ByRequestRememberPasswordChangeUserPasswordCommand $aCommand The command
     *
     * @throws UserTokenNotFoundException when the user does not exist
     * @throws UserTokenExpiredException  when the token is expired
     */
    public function __invoke(ByRequestRememberPasswordChangeUserPasswordCommand $aCommand)
    {
        $user = $this->repository->userOfRememberPasswordToken(new UserToken($aCommand->rememberPasswordToken()));
        if (null === $user) {
            throw new UserTokenNotFoundException();
        }
        if ($user->isRememberPasswordTokenExpired()) {
            throw new UserTokenExpiredException();
        }
        $user->changePassword(UserPassword::fromPlain($aCommand->newPlainPassword(), $this->encoder));

        $this->repository->persist($user);
    }
}
