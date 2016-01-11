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
use BenGor\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGor\User\Domain\Model\UserFactory;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use BenGor\User\Domain\Model\UserToken;
use Ddd\Application\Service\ApplicationService;

/**
 * Sign up user by invitation service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class SignUpUserByInvitationService implements ApplicationService
{
    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * The user guest repository.
     *
     * @var UserGuestRepository
     */
    private $userGuestRepository;

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
     * @param UserRepository      $aUserRepository      The user repository
     * @param UserGuestRepository $aUserGuestRepository The user guest repository
     * @param UserPasswordEncoder $anEncoder            The password encoder
     * @param UserFactory         $aFactory             The user factory
     */
    public function __construct(
        UserRepository $aUserRepository,
        UserGuestRepository $aUserGuestRepository,
        UserPasswordEncoder $anEncoder,
        UserFactory $aFactory
    ) {
        $this->userRepository = $aUserRepository;
        $this->userGuestRepository = $aUserGuestRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request = null)
    {
        $token = $request->invitationToken();
        $password = $request->password();
        $roles = $request->roles();

        $userGuest = $this->userGuestRepository->userGuestOfInvitationToken(new UserToken($token));
        if (null === $userGuest) {
            throw new UserGuestDoesNotExistException();
        }
        if (null !== $this->userRepository->userOfEmail($userGuest->email())) {
            throw new UserAlreadyExistException();
        }
        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, $roles);

        $user = $this->factory->register(
            $this->userRepository->nextIdentity(),
            $userGuest->email(),
            UserPassword::fromPlain($password, $this->encoder),
            $userRoles
        );

        $this->userRepository->persist($user);
        $this->userGuestRepository->remove($userGuest);

        return new SignUpUserByInvitationResponse($user);
    }
}
