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

use BenGor\User\Infrastructure\Persistence\Doctrine\ObjectManagerFactory;
use BenGor\User\Infrastructure\Persistence\Doctrine\ORM\Types\UserGuestIdType;
use BenGor\User\Infrastructure\Persistence\Doctrine\ORM\Types\UserIdType;
use BenGor\User\Infrastructure\Persistence\Doctrine\ORM\Types\UserRolesType;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Doctrine ORM entity manager factory class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class EntityManagerFactory implements ObjectManagerFactory
{
    /**
     * {@inheritdoc}
     */
    public function build($aConnection, $isDevMode = true)
    {
        Type::addType('user_id', UserIdType::class);
        Type::addType('user_guest_id', UserGuestIdType::class);
        Type::addType('user_roles', UserRolesType::class);

        return EntityManager::create(
            $aConnection,
            Setup::createYAMLMetadataConfiguration([__DIR__ . '/Mapping'], $isDevMode)
        );
    }
}
