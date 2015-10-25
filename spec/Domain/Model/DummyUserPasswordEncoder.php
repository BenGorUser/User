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

namespace spec\BenGor\User\Domain\Model;

use BenGor\User\Domain\Model\UserPasswordEncoder;

class DummyUserPasswordEncoder implements UserPasswordEncoder
{
    /**
     * @var string
     */
    private $expectedResponse;

    /**
     * @param $expectedResponse
     */
    public function __construct($expectedResponse) {

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
