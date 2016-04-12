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

namespace BenGor\User\Domain\Model;

/**
 * User url generator domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface UserUrlGenerator
{
    /**
     * Generates an absolute URL, e.g. "http://bengor-user.com/bengor/user".
     */
    const ABSOLUTE_URL = 0;

    /**
     * Generates an absolute path, e.g. "/bengor/user".
     */
    const ABSOLUTE_PATH = 1;

    /**
     * Generates a URL or path for a specific route based on the given parameters.
     *
     * @param string $aName          The name of the route
     * @param mixed  $parameters     An array of parameters
     * @param int    $aReferenceType The type of reference to be generated (one of the constants)
     *
     * @return string
     */
    public function generate($aName, $parameters = [], $aReferenceType = self::ABSOLUTE_URL);
}
