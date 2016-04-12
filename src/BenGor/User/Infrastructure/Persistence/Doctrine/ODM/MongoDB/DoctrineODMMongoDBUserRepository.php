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

namespace BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserToken;
use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Doctrine ODM MongoDB user repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class DoctrineODMMongoDBUserRepository extends DocumentRepository implements UserRepository
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
        return $this->findOneBy(['email' => $anEmail->email()]);
    }

    /**
     * {@inheritdoc}
     */
    public function userOfConfirmationToken(UserToken $aConfirmationToken)
    {
        return $this->findOneBy(['confirmationToken' => $aConfirmationToken->token()]);
    }

    /**
     * {@inheritdoc}
     */
    public function userOfRememberPasswordToken(UserToken $aRememberPasswordToken)
    {
        return $this->findOneBy(['rememberPasswordToken' => $aRememberPasswordToken->token()]);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $aUser)
    {
        $this->getDocumentManager()->persist($aUser);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $aUser)
    {
        $this->getDocumentManager()->remove($aUser);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        $queryBuilder = $this->createQueryBuilder();

        return $queryBuilder
            ->count()
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}
