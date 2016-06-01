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

namespace BenGorUser\User\Application\Command\Invite;

use BenGorUser\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGorUser\User\Domain\Model\Exception\UserInvitationAlreadyAcceptedException;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactory;
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
     * The user factory.
     *
     * @var UserFactory
     */
    private $userFactory;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Constructor.
     *
     * @param UserRepository $aUserRepository The user repository
     * @param UserFactory    $aUserFactory    The user factory
     */
    public function __construct(UserRepository $aUserRepository, UserFactory $aUserFactory)
    {
        $this->userRepository = $aUserRepository;
        $this->userGuestFactory = $aUserFactory;
    }

    /**
     * Handles the given command.
     *
     * @param InviteUserCommand $aCommand The command
     *
     * @throws UserInvitationAlreadyAcceptedException when the user already accepted invitation
     * @throws UserAlreadyExistException when the user already exists
     */
    public function __invoke(InviteUserCommand $aCommand)
    {
        $email = new UserEmail($aCommand->email());

        $user = $this->userRepository->userOfEmail($email);
        if (null !== $user && null === $user->invitationToken()) {
            throw new UserInvitationAlreadyAcceptedException();
        }

        $userGuest = $this->userGuestRepository->userGuestOfEmail($email);
        if (null === $user) {
            $userGuest = $this->userGuestFactory->register(
                $this->userGuestRepository->nextIdentity(),
                $email
            );
        } else {
            $user->regenerateInvitationToken();
        }

        $this->userRepository->persist($user);
    }
}
