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

use BenGor\User\Infrastructure\Persistence\Doctrine\ObjectManagerFactory;
use BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types\UserEmailType;
use BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types\UserGuestIdType;
use BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types\UserIdType;
use BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types\UserPasswordType;
use BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types\UserRolesType;
use BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types\UserTokenType;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\YamlDriver;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Doctrine ODM MongoDB document manager factory class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DocumentManagerFactory implements ObjectManagerFactory
{
    /**
     * {@inheritdoc}
     */
    public function build($aConnection, $isDevMode = true)
    {
        Type::addType('user_email', UserEmailType::class);
        Type::addType('user_guest_id', UserGuestIdType::class);
        Type::addType('user_id', UserIdType::class);
        Type::addType('user_password', UserPasswordType::class);
        Type::addType('user_roles', UserRolesType::class);
        Type::addType('user_token', UserTokenType::class);

        $configuration = new Configuration();
        $driver = new YamlDriver([__DIR__ . '/Mapping']);
        $configuration->setMetadataDriverImpl($driver);

        return DocumentManager::create($aConnection, $configuration);
    }
}
