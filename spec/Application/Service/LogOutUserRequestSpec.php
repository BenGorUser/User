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

namespace spec\BenGor\User\Application\Service;

use BenGor\User\Application\Service\LogOutUserRequest;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of logout user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogOutUserRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('id');
        $this->shouldHaveType(LogOutUserRequest::class);

        $this->id()->shouldBe('id');
    }
}
