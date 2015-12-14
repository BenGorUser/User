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

use BenGor\User\Application\Service\ActivateUserAccountRequest;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of activate user account request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ActivateUserAccountRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $confirmationToken = 'asojasiudasjuidsajiu';

        $this->beConstructedWith($confirmationToken);
        $this->shouldHaveType(ActivateUserAccountRequest::class);

        $this->confirmationToken()->shouldBe($confirmationToken);
    }
}
