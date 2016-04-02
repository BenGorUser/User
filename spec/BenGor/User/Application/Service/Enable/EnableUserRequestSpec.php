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

namespace spec\BenGor\User\Application\Service\Enable;

use BenGor\User\Application\Service\Enable\EnableUserRequest;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of enable user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class EnableUserRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $confirmationToken = 'asojasiudasjuidsajiu';

        $this->beConstructedWith($confirmationToken);
        $this->shouldHaveType(EnableUserRequest::class);

        $this->confirmationToken()->shouldReturn($confirmationToken);
    }
}
