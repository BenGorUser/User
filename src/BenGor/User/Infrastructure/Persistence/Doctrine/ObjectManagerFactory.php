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

namespace BenGor\User\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Doctrine object document manager factory.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface ObjectManagerFactory
{
    /**
     * Creates an object manager instance with
     * the library's mappings and custom types.
     *
     * @param mixed $aConnection Connection parameters as db driver
     * @param bool  $isDevMode   Enables the dev mode, by default is enabled
     *
     * @return ObjectManager
     */
    public function build($aConnection, $isDevMode = true);
}
