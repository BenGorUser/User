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

namespace spec\BenGor\User\Domain\Model;

use BenGor\User\Domain\Model\UserPasswordEncoder;

/**
 * Dummy user password encoder class for testing purposes.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class DummyUserPasswordEncoder implements UserPasswordEncoder
{
    /**
     * The expected response.
     *
     * @var string
     */
    private $expectedResponse;

    /**
     * Constructor.
     *
     * @param string $expectedResponse The expected response
     */
    public function __construct($expectedResponse)
    {
        $this->expectedResponse = $expectedResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($aPlainPassword, $aSalt)
    {
        return $this->expectedResponse;
    }
}
