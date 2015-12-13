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

namespace Lw\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Doctrine entity manager factory class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class EntityManagerFactory
{
    /**
     * Decorates the doctrine entity manager
     * with library's mappings and custom types.
     *
     * @param mixed $aConnection Connection parameters as db driver
     * @param bool  $isDevMode   Enables the dev mode, by default is enabled
     *
     * @return EntityManager
     */
    public function build($aConnection, $isDevMode = true)
    {
        Type::addType('user_roles', 'BenGor\User\Infrastructure\Persistence\Types\UserRolesType');

        return EntityManager::create(
            $aConnection,
            Setup::createYAMLMetadataConfiguration([__DIR__ . '/Mapping'], $isDevMode)
        );
    }
}
