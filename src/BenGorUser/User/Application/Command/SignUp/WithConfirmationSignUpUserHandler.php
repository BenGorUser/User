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

namespace BenGorUser\User\Application\Command\SignUp;

use BenGorUser\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactorySignUp;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;

/**
 * With confirmation sign up user user command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class WithConfirmationSignUpUserHandler
{
    /**
     * The user password encoder.
     *
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * The user sign up factory.
     *
     * @var UserFactorySignUp
     */
    private $factory;

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
     * @param UserFactorySignUp   $aFactory    The user sign up factory
     */
    public function __construct(
        UserRepository $aRepository,
        UserPasswordEncoder $anEncoder,
        UserFactorySignUp $aFactory
    ) {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
    }

    /**
     * Handles the given command.
     *
     * @param WithConfirmationSignUpUserCommand $aCommand The command
     *
     * @throws UserAlreadyExistException when the user id is already exists
     */
    public function __invoke(WithConfirmationSignUpUserCommand $aCommand)
    {
        $id = new UserId($aCommand->id());
        if (null !== $this->repository->userOfId($id)) {
            throw new UserAlreadyExistException();
        }
        $email = new UserEmail($aCommand->email());
        if (null !== $this->repository->userOfEmail($email)) {
            throw new UserAlreadyExistException();
        }

        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, $aCommand->roles());

        $user = $this->factory->build(
            $id,
            $email,
            UserPassword::fromPlain($aCommand->password(), $this->encoder),
            $userRoles
        );

        $this->repository->persist($user);
    }
}
