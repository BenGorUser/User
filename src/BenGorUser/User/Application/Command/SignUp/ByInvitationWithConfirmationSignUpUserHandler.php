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

use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
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
     * The user password encoder.
     *
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Constructor.
     *
     * @param UserRepository      $aUserRepository The user repository
     * @param UserPasswordEncoder $anEncoder       The password encoder
     */
    public function __construct(UserRepository $aUserRepository, UserPasswordEncoder $anEncoder)
    {
        $this->userRepository = $aUserRepository;
        $this->encoder = $anEncoder;
    }

    /**
     * Handles the given command.
     *
     * @param ByInvitationWithConfirmationSignUpUserCommand $aCommand The command
     *
     * @throws UserDoesNotExistException when the user id is already exists
     */
    public function __invoke(ByInvitationWithConfirmationSignUpUserCommand $aCommand)
    {
        $user = $this->userRepository->userOfInvitationToken(
            new UserToken($aCommand->invitationToken())
        );
        if (null === $user) {
            throw new UserDoesNotExistException();
        }
        $user->changePassword(UserPassword::fromPlain($aCommand->password(), $this->encoder));

        $this->userRepository->persist($user);
    }
}
