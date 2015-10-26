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
 * Spec file of request remember user password request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RequestRememberUserPasswordRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('test@test.com');
        $this->shouldHaveType('BenGor\User\Application\Service\RequestRememberUserPasswordRequest');

        $this->email()->shouldBe('test@test.com');
    }
}
