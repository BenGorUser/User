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

namespace BenGorUser\User\Application\Service\ChangePassword;

use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * Change user password command handler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordHandler
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
    private $repository;

    /**
     * The service specification.
     *
     * @var ChangeUserPasswordSpecification
     */
    private $specification;

    /**
     * Constructor.
     *
     * @param UserRepository                  $aRepository    The user repository
     * @param UserPasswordEncoder             $anEncoder      The password encoder
     * @param ChangeUserPasswordSpecification $aSpecification The service specification
     */
    public function __construct(
        UserRepository $aRepository,
        UserPasswordEncoder $anEncoder,
        ChangeUserPasswordSpecification $aSpecification
    ) {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
        $this->specification = $aSpecification;
    }

    /**
     * Handles the given command.
     *
     * @param ChangeUserPasswordCommand $aCommand The command
     */
    public function __invoke(ChangeUserPasswordCommand $aCommand)
    {
        $user = $this->specification->user($aCommand);
        $user->changePassword(UserPassword::fromPlain($aCommand->newPlainPassword(), $this->encoder));

        $this->repository->persist($user);
    }
}
