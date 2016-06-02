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

namespace spec\BenGorUser\User\Application\Command\CommandRememberPassword;

use BenGorUser\User\Application\Command\CommandRememberPassword\CommandRememberPasswordCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of request remember password request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RequestRememberPasswordCommandSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('test@test.com');
        $this->shouldHaveType(CommandRememberPasswordCommand::class);

        $this->email()->shouldBe('test@test.com');
    }
}
