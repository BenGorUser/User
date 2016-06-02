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
use BenGorUser\User\Domain\Model\UserFactorySignUp;
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
     * @var UserFactorySignUp
     */
    private $factory;

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
     * @param UserFactorySignUp   $aFactory        The user sign up factory
     */
    public function __construct(
        UserRepository $aUserRepository,
        UserPasswordEncoder $anEncoder,
        UserFactorySignUp $aFactory
    ) {
        $this->userRepository = $aUserRepository;
        $this->encoder = $anEncoder;
        $this->factory = $aFactory;
    }

    /**
     * Handles the given command.
     *
     * @param ByInvitationSignUpUserCommand $aCommand The command
     *
     * @throws UserDoesNotExistException when the user does not exist
     */
    public function __invoke(ByInvitationSignUpUserCommand $aCommand)
    {
        $user = $this->userRepository->userOfInvitationToken(
            new UserToken($aCommand->invitationToken())
        );
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        foreach ($aCommand->roles() as $role) {
            $user->grant(new UserRole($role));
        }
        $user->changePassword(UserPassword::fromPlain($aCommand->password(), $this->encoder));
        $user->enableAccount();

        $this->userRepository->persist($user);
    }
}
