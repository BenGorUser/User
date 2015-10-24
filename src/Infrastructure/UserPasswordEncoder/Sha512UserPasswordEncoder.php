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

namespace BenGor\User\Infrastructure\UserPasswordEncoder;

use BenGor\User\Domain\Model\UserPasswordEncoder;

/**
 * Sha512 user password encoder class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class Sha512UserPasswordEncoder implements UserPasswordEncoder
{
    /**
     * {@inheritdoc}
     */
    public function encode($aPlainPassword, $aSalt)
    {
        // TODO: Implement encode() method.
    }
}
