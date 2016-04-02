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

namespace BenGor\User\Application\Service\SignUp;

use BenGor\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserToken;

/**
 * By invitation and with confirmation specification of sign up user service.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SpecificationByInvitationWithConfirmationSignUpUser implements SpecificationSignUpUser
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
    public function email(SignUpUserRequest $request)
    {
        $userGuest = $this->repository->userGuestOfInvitationToken(
            new UserToken(
                $request->invitationToken()
            )
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
        return $aUser;
    }
}
