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

namespace BenGor\User\Infrastructure\Persistence\Doctrine\ORM;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserToken;
use Doctrine\ORM\EntityRepository;

/**
 * Doctrine ORM user repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class DoctrineORMUserRepository extends EntityRepository implements UserRepository
{
    /**
     * {@inheritdoc}
     */
    public function userOfId(UserId $anId)
    {
        return $this->find($anId->id());
    }

    /**
     * {@inheritdoc}
     */
    public function userOfEmail(UserEmail $anEmail)
    {
        return $this->findOneBy(['email.email' => $anEmail->email()]);
    }

    /**
     * {@inheritdoc}
     */
    public function userOfConfirmationToken(UserToken $aConfirmationToken)
    {
        return $this->findOneBy(['confirmationToken.token' => $aConfirmationToken->token()]);
    }

    /**
     * {@inheritdoc}
     */
    public function userOfRememberPasswordToken(UserToken $aRememberPasswordToken)
    {
        return $this->findOneBy(['rememberPasswordToken.token' => $aRememberPasswordToken->token()]);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $aUser)
    {
        $this->getEntityManager()->persist($aUser);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $aUser)
    {
        $this->getEntityManager()->remove($aUser);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        $queryBuilder = $this->createQueryBuilder('u');

        return $queryBuilder
            ->select($queryBuilder->expr()->count('u.id.id'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}
