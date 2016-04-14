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

namespace BenGor\User\Infrastructure\Persistence\Doctrine\ODM\MongoDB\Types;

use BenGor\User\Domain\Model\UserId;
use Doctrine\ODM\MongoDB\Types\IdType;

/**
 * Doctrine ODM MongoDB user id custom type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserIdType extends IdType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        if ($value === null) {
            return;
        }
        if (!$value instanceof \MongoId) {
            try {
                $value = $value instanceof UserId
                    ? new \MongoId($value->id())
                    : new \MongoId($value);
            } catch (\MongoException $e) {
                $value = new \MongoId();
            }
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        return $value instanceof \MongoId ? new UserId((string)$value) : new UserId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function closureToMongo()
    {
        return '$return = $value instanceof UserId ? new MongoId($value->id()) : new \MongoId($value);';
    }

    /**
     * {@inheritdoc}
     */
    public function closureToPHP()
    {
        return '$return = $value instanceof \MongoId ' .
        '? new \BenGor\User\Domain\Model\UserId((string)$value) ' .
        ': new \BenGor\User\Domain\Model\UserId($value);';
    }
}
