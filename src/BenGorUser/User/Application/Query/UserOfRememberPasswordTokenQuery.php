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

namespace BenGorUser\User\Application\Query;

/**
 * User of remember password token query.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfRememberPasswordTokenQuery
{
    /**
     * The user remember password token.
     *
     * @var string
     */
    private $rememberPasswordToken;

    /**
     * Constructor.
     *
     * @param string $anRememberPasswordToken The user remember password token
     */
    public function __construct($anRememberPasswordToken)
    {
        if (null === $anRememberPasswordToken) {
            throw new \InvalidArgumentException('Remember password token cannot be null');
        }
        $this->rememberPasswordToken = $anRememberPasswordToken;
    }

    /**
     * Gets the remember password token.
     *
     * @return string
     */
    public function rememberPasswordToken()
    {
        return $this->rememberPasswordToken;
    }
}
