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

use BenGor\User\Domain\Model\UserToken;
use Doctrine\ODM\MongoDB\Types\StringType;

/**
 * Doctrine ODM MongoDB user email custom type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserTokenType extends StringType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value)
    {
        return $value->token();
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        return new UserToken($value);
    }
}
