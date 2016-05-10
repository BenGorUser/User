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

namespace BenGorUser\User\Domain\Model;

/**
 * User password encoder domain interface.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserPasswordEncoder
{
    /**
     * Encodes the plain password.
     *
     * @param string $aPlainPassword A plain password
     * @param string $aSalt          The salt
     *
     * @return string
     */
    public function encode($aPlainPassword, $aSalt);

    /**
     * Checks a raw password against an encoded password.
     *
     * @param UserPassword $anEncoded      An encoded domain password
     * @param string       $aPlainPassword A plain password
     *
     * @return bool
     */
    public function isPasswordValid(UserPassword $anEncoded, $aPlainPassword);
}
