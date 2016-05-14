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
use BenGorUser\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGorUser\User\Domain\Model\UserFactory;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Domain\Model\UserToken;

/**
 * By invitation with confirmation sign up user user command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationWithConfirmationSignUpUserHandler
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
    private $userRepository;

    /**
     * The user guest repository.
     *
     * @var UserGuestRepository
     */
    private $userGuestRepository;

    /**
     * Constructor.
     *
     * @param UserRepository      $aUserRepository      The user repository
     * @param UserPasswordEncoder $anEncoder            The password encoder
     * @param UserFactory         $aFactory             The user factory
     * @param UserDataTransformer $aDataTransformer     The user data transformer
     * @param UserGuestRepository $aUserGuestRepository The user guest repository
     */
    public function __construct(
        UserRepository $aUserRepository,
        UserPasswordEncoder $anEncoder,
        UserFactory $aFactory,
        UserDataTransformer $aDataTransformer,
        UserGuestRepository $aUserGuestRepository
    ) {
        $this->userRepository = $aUserRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
        $this->dataTransformer = $aDataTransformer;
        $this->userGuestRepository = $aUserGuestRepository;
    }

    /**
     * Handles the given command.
     *
     * @param ByInvitationWithConfirmationSignUpUserCommand $aCommand The command
     *
     * @throws UserGuestDoesNotExistException when the user guest does not exist
     *
     * @return mixed
     */
    public function __invoke(ByInvitationWithConfirmationSignUpUserCommand $aCommand)
    {
        $userGuest = $this->userGuestRepository->userGuestOfInvitationToken(
            new UserToken($aCommand->invitationToken())
        );
        if (null === $userGuest) {
            throw new UserGuestDoesNotExistException();
        }
        $email = $userGuest->email();
        $this->userGuestRepository->remove($userGuest);

        $userRoles = array_map(function ($role) {
            return new UserRole($role);
        }, $aCommand->roles());

        $user = $this->factory->register(
            $this->userRepository->nextIdentity(),
            $email,
            UserPassword::fromPlain($aCommand->password(), $this->encoder),
            $userRoles
        );

        $this->userRepository->persist($user);
        $this->dataTransformer->write($user);

        return $this->dataTransformer->read();
    }
}
