<?php

/*
 * This file is part of the BenGorUser package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorUser\User\Application\DataTransformer;

/**
 * User data transformer.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface UserDataTransformer
{
    /**
     * Writes the high level user domain concepts.
     *
     * @param mixed $aUser The user, it can be domain user or just a DTO
     */
    public function write($aUser);

    /**
     * Reads the low level user infrastructure details.
     *
     * @return mixed
     */
    public function read();
}
