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

namespace spec\BenGorUser\User\Application\Command\Remove;

use BenGorUser\User\Application\Command\Remove\RemoveUserCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of RemoveUserCommand class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RemoveUserCommandSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('a-plain-string-id');
        $this->shouldHaveType(RemoveUserCommand::class);
        $this->id()->shouldReturn('a-plain-string-id');
    }
}
