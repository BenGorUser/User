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

namespace BenGorUser\User\Application\Service\Invite;

use BenGorUser\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuestFactory;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Invite user command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class InviteUserHandler
{
    /**
     * The user guest factory.
     *
     * @var UserGuestFactory
     */
    private $userGuestFactory;

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
     * @param UserGuestRepository $aUserGuestRepository The user guest repository
     * @param UserGuestFactory    $aUserGuestFactory    The user guest factory
     */
    public function __construct(
        UserRepository $aUserRepository,
        UserGuestRepository $aUserGuestRepository,
        UserGuestFactory $aUserGuestFactory
    ) {
        $this->userRepository = $aUserRepository;
        $this->userGuestRepository = $aUserGuestRepository;
        $this->userGuestFactory = $aUserGuestFactory;
    }

    /**
     * Handles the given command.
     *
     * @param InviteUserCommand $aCommand The command
     *
     * @throws UserAlreadyExistException when the user already exists
     */
    public function __invoke(InviteUserCommand $aCommand)
    {
        $email = new UserEmail($aCommand->email());

        $user = $this->userRepository->userOfEmail($email);
        if (null !== $user) {
            throw new UserAlreadyExistException();
        }

        $userGuest = $this->userGuestRepository->userGuestOfEmail($email);
        if (null === $userGuest) {
            $userGuest = $this->userGuestFactory->register(
                $this->userGuestRepository->nextIdentity(),
                $email
            );
        } else {
            $userGuest->regenerateInvitationToken();
        }

        $this->userGuestRepository->persist($userGuest);
    }
}
