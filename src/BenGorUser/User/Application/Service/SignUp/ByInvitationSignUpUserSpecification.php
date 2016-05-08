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

namespace BenGorUser\User\Application\Service\SignUp;

use BenGorUser\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserToken;

/**
 * By invitation specification of sign up user command handler.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ByInvitationSignUpUserSpecification implements SignUpUserSpecification
{
    /**
     * The user guest repository.
     *
     * @var UserGuestRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param UserGuestRepository $aRepository The user guest repository
     */
    public function __construct(UserGuestRepository $aRepository)
    {
        $this->repository = $aRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function email(SignUpUserCommand $aCommand)
    {
        $userGuest = $this->repository->userGuestOfInvitationToken(
            new UserToken($aCommand->invitationToken())
        );
        if (null === $userGuest) {
            throw new UserGuestDoesNotExistException();
        }
        $email = $userGuest->email();
        $this->repository->remove($userGuest);

        return $email;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(User $aUser)
    {
        $aUser->enableAccount();

        return $aUser;
    }
}
