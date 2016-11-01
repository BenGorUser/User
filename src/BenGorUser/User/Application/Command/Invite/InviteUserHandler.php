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
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserFactoryInvite;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;

/**
 * Invite user command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class InviteUserHandler
{
    /**
     * The user invite factory.
     *
     * @var UserFactoryInvite
     */
    private $factory;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param UserRepository    $aRepository The user repository
     * @param UserFactoryInvite $aFactory    The user invite factory
     */
    public function __construct(UserRepository $aRepository, UserFactoryInvite $aFactory)
    {
        $this->repository = $aRepository;
        $this->factory = $aFactory;
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

        $user = $this->repository->userOfEmail($email);
        if (null !== $user) {
            throw new UserAlreadyExistException();
        }

        $user = $this->factory->build(
            new UserId($aCommand->id()),
            $email,
            array_map(function ($role) {
                return new UserRole($role);
            }, $aCommand->roles())
        );

        $this->repository->persist($user);
    }
}
