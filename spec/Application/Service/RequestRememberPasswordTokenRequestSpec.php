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

namespace spec\BenGor\User\Application\Service;

use PhpSpec\ObjectBehavior;

/**
 * Spec file of request remember password token request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RequestRememberPasswordTokenRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('test@test.com');
        $this->shouldHaveType('BenGor\User\Application\Service\RequestRememberPasswordTokenRequest');

        $this->email()->shouldBe('test@test.com');
    }
}
