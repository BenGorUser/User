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

namespace BenGor\User\Application\Service\LogIn;

use BenGor\User\Application\DataTransformer\UserDataTransformer;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\Exception\UserInactiveException;
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use Ddd\Application\Service\ApplicationService;

/**
 * User login service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogInUserService implements ApplicationService
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
     * The user data transformer.
     *
     * @var UserDataTransformer
     */
    private $dataTransformer;

    /**
     * Constructor.
     *
     * @param UserRepository      $aRepository      The user repository
     * @param UserPasswordEncoder $anEncoder        The password encoder
     * @param UserDataTransformer $aDataTransformer The user data transformer
     */
    public function __construct(
        UserRepository $aRepository,
        UserPasswordEncoder $anEncoder,
        UserDataTransformer $aDataTransformer
    ) {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
        $this->dataTransformer = $aDataTransformer;
    }

    /**
     * Executes application service.
     *
     * @param LogInUserRequest $request The request
     *
     * @throws UserDoesNotExistException    when the user does not exist
     * @throws UserInactiveException        when the user is not enabled
     * @throws UserPasswordInvalidException when the user password is invalid
     *
     * @return mixed
     */
    public function execute($request = null)
    {
        $email = $request->email();
        $plainPassword = $request->password();

        $user = $this->repository->userOfEmail(new UserEmail($email));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $user->login($plainPassword, $this->encoder);

        $this->repository->persist($user);
        $this->dataTransformer->write($user);

        return $this->dataTransformer->read();
    }
}
