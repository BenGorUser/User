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

namespace BenGorUser\User\Infrastructure\Security\Test;

use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;

/**
 * Dummy user password encoder class for testing purposes.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class DummyUserPasswordEncoder implements UserPasswordEncoder
{
    /**
     * The expected response.
     *
     * @var string
     */
    private $expectedResponse;

    /**
     * Is password valid boolean.
     *
     * @var bool
     */
    private $isPasswordValid;

    /**
     * Constructor.
     *
     * @param string $expectedResponse The expected response
     * @param bool   $isPasswordValid  Is password valid boolean, by default is true
     */
    public function __construct($expectedResponse, $isPasswordValid = true)
    {
        $this->expectedResponse = $expectedResponse;
        $this->isPasswordValid = $isPasswordValid;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($aPlainPassword, $aSalt)
    {
        return $this->expectedResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid(UserPassword $anEncoded, $aPlainPassword)
    {
        return $this->isPasswordValid;
    }
}
