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

namespace BenGor\User\Application\Service;

use BenGor\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserFactory;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use Ddd\Application\Service\ApplicationService;

/**
 * Sign up user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class SignUpUserService implements ApplicationService
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
     * The user factory.
     *
     * @var UserFactory
     */
    private $factory;

    /**
     * Constructor.
     *
     * @param UserRepository      $aRepository The user repository
     * @param UserPasswordEncoder $anEncoder   The password encoder
     * @param UserFactory         $aFactory    The user factory
     */
    public function __construct(UserRepository $aRepository, UserPasswordEncoder $anEncoder, UserFactory $aFactory)
    {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request = null)
    {
        $email = $request->email();
        $password = $request->password();
        $roles = $request->roles();

        $email = new UserEmail($email);
        if (null !== $this->repository->userOfEmail($email)) {
            throw new UserAlreadyExistException();
        }

        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, $roles);

        $user = $this->factory->register(
            $this->repository->nextIdentity(),
            $email,
            UserPassword::fromPlain($password, $this->encoder),
            $userRoles
        );

        $this->repository->persist($user);

        return new SignUpUserResponse($user);
    }
}
