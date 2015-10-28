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

namespace BenGor\User\Infrastructure\Persistence\Doctrine;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserToken;
use Doctrine\ORM\EntityManager;

/**
 * Doctrine user repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class DoctrineUserRepository implements UserRepository
{
    /**
     * The entity fully qualified namespace.
     *
     * @var string
     */
    private $class;

    /**
     * The entity manager.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManager $aManager The entity manager
     * @param string        $aClass   The entity fully qualified namespace
     */
    public function __construct(EntityManager $aManager, $aClass = 'BenGor\User\Domain\Model\User')
    {
        $this->class = $aClass;
        $this->entityManager = $aManager;
    }

    /**
     * {@inheritdoc}
     */
    public function userOfId(UserId $anId)
    {
        return $this->entityManager->find($this->class, $anId);
    }

    /**
     * {@inheritdoc}
     */
    public function userOfEmail(UserEmail $anEmail)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return $queryBuilder
            ->select($queryBuilder->expr()->eq('u.email', ':email'))
            ->from($this->class, 'u')
            ->setParameter(':email', $anEmail)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function userOfConfirmationToken(UserToken $aConfirmationToken)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return $queryBuilder
            ->select($queryBuilder->expr()->eq('u.confirmationToken', ':confirmationToken'))
            ->from($this->class, 'u')
            ->setParameter(':confirmationToken', $aConfirmationToken)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function userOfRememberPasswordToken(UserToken $aRememberPasswordToken)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return $queryBuilder
            ->select($queryBuilder->expr()->eq('u.rememberPasswordToken', ':rememberPasswordToken'))
            ->from($this->class, 'u')
            ->setParameter(':rememberPasswordToken', $aRememberPasswordToken)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function query($specification)
    {
        // TODO: Implement query() method.
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $aUser)
    {
        $this->entityManager->persist($aUser);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $aUser)
    {
        $this->entityManager->remove($aUser);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return $queryBuilder
            ->select($queryBuilder->expr()->count('u.id'))
            ->from($this->class, 'u')
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
