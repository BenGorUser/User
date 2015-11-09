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

namespace BenGor\User\Infrastructure\Persistence\Doctrine;

use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserGuest;
use BenGor\User\Domain\Model\UserGuestId;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserToken;
use Doctrine\ORM\EntityRepository;

/**
 * Doctrine user guest repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class DoctrineUserGuestRepository extends EntityRepository implements UserGuestRepository
{
    /**
     * {@inheritdoc}
     */
    public function userGuestOfId(UserGuestId $anId)
    {
        return $this->findOneBy(['id.id' => $anId->id()]);
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfEmail(UserEmail $anEmail)
    {
        return $this->findOneBy(['email.email' => $anEmail->email()]);
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfInvitationToken(UserToken $anInvitationToken)
    {
        return $this->findOneBy(['invitationToken.token' => $anInvitationToken->token()]);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(UserGuest $aUserGuest)
    {
        $this->getEntityManager()->persist($aUserGuest);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(UserGuest $aUserGuest)
    {
        $this->getEntityManager()->remove($aUserGuest);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        $queryBuilder = $this->createQueryBuilder('ug');

        return $queryBuilder
            ->select($queryBuilder->expr()->count('ug.id.id'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserGuestId();
    }
}
