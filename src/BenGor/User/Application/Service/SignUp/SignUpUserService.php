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

namespace BenGor\User\Application\Service\SignUp;

use BenGor\User\Application\DataTransformer\UserDataTransformer;
use BenGor\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserFactory;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use Ddd\Application\Service\ApplicationService;

/**
 * Sign up user user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SignUpUserService implements ApplicationService
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
     * The service specification.
     *
     * @var SignUpUserSpecification
     */
    private $specification;

    /**
     * Constructor.
     *
     * @param UserRepository          $aRepository      The user repository
     * @param UserPasswordEncoder     $anEncoder        The password encoder
     * @param UserFactory             $aFactory         The user factory
     * @param UserDataTransformer     $aDataTransformer The user data transformer
     * @param SignUpUserSpecification $aSpecification   The service specification
     */
    public function __construct(
        UserRepository $aRepository,
        UserPasswordEncoder $anEncoder,
        UserFactory $aFactory,
        UserDataTransformer $aDataTransformer,
        SignUpUserSpecification $aSpecification
    ) {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
        $this->dataTransformer = $aDataTransformer;
        $this->specification = $aSpecification;
    }

    /**
     * Executes application service.
     *
     * @param SignUpUserRequest $request The request
     *
     * @throws UserAlreadyExistException when the user alreay exists
     *
     * @return mixed
     */
    public function execute($request = null)
    {
        $email = $this->specification->email($request);

        if (null !== $this->repository->userOfEmail($email)) {
            throw new UserAlreadyExistException();
        }

        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, $request->roles());

        $user = $this->factory->register(
            $this->repository->nextIdentity(),
            $email,
            UserPassword::fromPlain($request->password(), $this->encoder),
            $userRoles
        );

        $user = $this->specification->prePersist($user);

        $this->repository->persist($user);
        $this->dataTransformer->write($user);

        return $this->dataTransformer->read();
    }
}
