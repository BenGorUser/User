<?php

namespace BenGor\User\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserConfirmationToken;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;

/**
 * Doctrine user repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class DoctrineUserRepository implements UserRepository
{
    /**
     * The entity manager.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManager $anEntityManager The entity manager
     */
    public function __construct(EntityManager $anEntityManager)
    {
        $this->entityManager = $anEntityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function userOfId(UserId $anId)
    {
        return $this->entityManager->findOneBy(['id' => $anId]);
    }

    /**
     * {@inheritdoc}
     */
    public function userOfEmail(UserEmail $anEmail)
    {
        return $this->entityManager->findOneBy(['email' => $anEmail]);
    }

    /**
     * {@inheritdoc}
     */
    public function userOfConfirmationToken(UserConfirmationToken $aConfirmationToken)
    {
        return $this->entityManager->findOneBy(['confirmationToken' => $aConfirmationToken]);
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
            ->from('BenGor\User\Domain\Model\User', 'u')
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
