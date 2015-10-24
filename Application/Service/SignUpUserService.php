<?php

namespace BenGor\User\Application\Service;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserAlreadyExistException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;

/**
 * Sign up user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class SignUpUserService implements ApplicationService
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
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
    public function execute($request = null)
    {
        $email = $request->email();
        $password = $request->password();

        if(null !== $this->repository->userOfEmail($email)) {
            throw new UserAlreadyExistException();
        }

        $user = User::register(
            new UserId(),
            new UserEmail($email),
            $password,
            $this->encoder
        );

        $this->repository->persist($user);
    }
}
