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

namespace BenGor\User\Application\Service\ChangePassword;

use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;

/**
 * Change user password service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordService
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
     * @var SpecificationChangeUserPassword
     */
    private $specification;

    /**
     * Constructor.
     *
     * @param UserRepository                  $aRepository    The user repository
     * @param UserPasswordEncoder             $anEncoder      The password encoder
     * @param SpecificationChangeUserPassword $aSpecification The service specification
     */
    public function __construct(
        UserRepository $aRepository,
        UserPasswordEncoder $anEncoder,
        SpecificationChangeUserPassword $aSpecification
    ) {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
        $this->specification = $aSpecification;
    }

    /**
     * Executes application service.
     *
     * @param ChangeUserPasswordRequest $request The request
     */
    public function execute(ChangeUserPasswordRequest $request)
    {
        $user = $this->specification->user($request);
        $user->changePassword(UserPassword::fromPlain($request->newPlainPassword(), $this->encoder));

        $this->repository->persist($user);
    }
}
