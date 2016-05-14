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

namespace BenGorUser\User\Application\Service\SignUp;

use BenGorUser\User\Application\DataTransformer\UserDataTransformer;
use BenGorUser\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactory;
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
     * The user data transformer.
     *
     * @var UserDataTransformer
     */
    private $dataTransformer;

    /**
     * The user password encoder.
     *
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * The user factory.
     *
     * @var UserFactory
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
     * @param UserRepository      $aRepository      The user repository
     * @param UserPasswordEncoder $anEncoder        The password encoder
     * @param UserFactory         $aFactory         The user factory
     * @param UserDataTransformer $aDataTransformer The user data transformer
     */
    public function __construct(
        UserRepository $aRepository,
        UserPasswordEncoder $anEncoder,
        UserFactory $aFactory,
        UserDataTransformer $aDataTransformer
    ) {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
        $this->dataTransformer = $aDataTransformer;
    }

    /**
     * Handles the given command.
     *
     * @param WithConfirmationSignUpUserCommand $aCommand The command
     *
     * @throws UserAlreadyExistException when the user alreay exists
     *
     * @return mixed
     */
    public function __invoke(WithConfirmationSignUpUserCommand $aCommand)
    {
        $email = new UserEmail($aCommand->email());

        if (null !== $this->repository->userOfEmail($email)) {
            throw new UserAlreadyExistException();
        }

        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, $aCommand->roles());

        $user = $this->factory->register(
            $this->repository->nextIdentity(),
            $email,
            UserPassword::fromPlain($aCommand->password(), $this->encoder),
            $userRoles
        );

        $this->repository->persist($user);
        $this->dataTransformer->write($user);

        return $this->dataTransformer->read();
    }
}
