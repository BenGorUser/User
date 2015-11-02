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

namespace BenGor\User\Application\Service;

use BenGor\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserGuest;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserRepository;
use Ddd\Application\Service\ApplicationService;

/**
 * Invite user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class InviteUserService implements ApplicationService
{
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
     * @param UserGuestRepository $aUserGuestRepository The password encoder
     */
    public function __construct(UserRepository $aUserRepository, UserGuestRepository $aUserGuestRepository)
    {
        $this->userRepository = $aUserRepository;
        $this->userGuestRepository = $aUserGuestRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request = null)
    {
        $email = $request->email();
        $email = new UserEmail($email);

        $user = $this->userRepository->userOfEmail($email);
        if (null !== $user) {
            throw new UserAlreadyExistException();
        }

        $userGuest = $this->userGuestRepository->userGuestOfEmail($email);
        if (null === $userGuest) {
            $userGuest = new UserGuest(
                $this->userGuestRepository->nextIdentity(),
                $email
            );
        } else {
            $userGuest->regenerateInvitationToken();
        }

        $this->userGuestRepository->persist($userGuest);
    }
}
