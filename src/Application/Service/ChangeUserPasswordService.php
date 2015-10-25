<?php

/*
 * This file is part of the User library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\User\Application\Service;

use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use Ddd\Application\Service\ApplicationService;

/**
 * Change user password service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class ChangeUserPasswordService implements ApplicationService
{
    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * The user password encoder.
     *
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * Constructor.
     *
     * @param UserRepository      $aRepository The user repository
     * @param UserPasswordEncoder $anEncoder   The password encoder
     */
    public function __construct(UserRepository $aRepository, UserPasswordEncoder $anEncoder)
    {
        $this->repository = $aRepository;
        $this->encoder = $anEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request = null)
    {
        $id = $request->id();
        $newPlainPassword = $request->newPlainPassword();
        $oldPlainPassword = $request->oldPlainPassword();

        $user = $this->repository->userOfId(new UserId($id));
        $oldPassword = UserPassword::fromPlain($oldPlainPassword, $this->encoder, $user->password()->salt());
        $newPassword = UserPassword::fromPlain($newPlainPassword, $this->encoder);

        $user->changePassword($oldPassword, $newPassword);

        $this->repository->persist($user);
    }
}
