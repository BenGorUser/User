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
 * User password encoder domain interface.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserPasswordEncoder
{
    /**
     * Encodes the given plain password with
     * salt given returning the encoded password.
     *
     * @param string $aPlainPassword The plain password
     * @param string $aSalt          The salt
     *
     * @return string
     */
    public function encode($aPlainPassword, $aSalt);
}
