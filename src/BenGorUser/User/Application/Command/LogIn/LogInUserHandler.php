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

namespace BenGorUser\User\Application\Command\LogIn;

use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * User login command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogInUserHandler
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
     * Handles the given command.
     *
     * @param LogInUserCommand $aCommand The command
     *
     * @throws UserDoesNotExistException when the user does not exist
     *
     * @return mixed
     */
    public function __invoke(LogInUserCommand $aCommand)
    {
        $user = $this->repository->userOfEmail(new UserEmail($aCommand->email()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        $user->login($aCommand->password(), $this->encoder);

        $this->repository->persist($user);
    }
}
