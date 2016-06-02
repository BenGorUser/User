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

namespace spec\BenGorUser\User\Application\Command\LogOut;

use BenGorUser\User\Application\Command\LogOut\LogOutUserCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of logout user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogOutUserCommandSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('id');
        $this->shouldHaveType(LogOutUserCommand::class);

        $this->id()->shouldReturn('id');
    }
}
