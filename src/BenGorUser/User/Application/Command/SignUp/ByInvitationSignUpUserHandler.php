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
use BenGorUser\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGorUser\User\Domain\Model\UserFactory;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Domain\Model\UserToken;

/**
 * By invitation sign up user user command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationSignUpUserHandler
{
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
     * @param UserGuestRepository $aUserGuestRepository The user guest repository
     */
    public function __construct(
        UserRepository $aUserRepository,
        UserPasswordEncoder $anEncoder,
        UserFactory $aFactory,
        UserGuestRepository $aUserGuestRepository
    ) {
        $this->userRepository = $aUserRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
        $this->userGuestRepository = $aUserGuestRepository;
    }

    /**
     * Handles the given command.
     *
     * @param ByInvitationSignUpUserCommand $aCommand The command
     *
     * @throws UserAlreadyExistException      when the user id is already exists
     * @throws UserGuestDoesNotExistException when the user guest does not exist
     */
    public function __invoke(ByInvitationSignUpUserCommand $aCommand)
    {
        $id = new UserId($aCommand->id());
        if (null !== $this->userRepository->userOfId($id)) {
            throw new UserAlreadyExistException();
        }
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
            $id,
            $email,
            UserPassword::fromPlain($aCommand->password(), $this->encoder),
            $userRoles
        );
        $user->enableAccount();

        $this->userRepository->persist($user);
    }
}
