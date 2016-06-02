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

namespace spec\BenGorUser\User\Application\Command\ChangePassword;

use BenGorUser\User\Application\Command\ChangePassword\ChangeUserPasswordCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of ChangeUserPasswordCommand class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordCommandSpec extends ObjectBehavior
{
    function it_creates_a_command()
    {
        $this->beConstructedWith('id', 'newPassword', 'oldPassword');
        $this->shouldHaveType(ChangeUserPasswordCommand::class);

        $this->id()->shouldReturn('id');
        $this->newPlainPassword()->shouldReturn('newPassword');
        $this->oldPlainPassword()->shouldReturn('oldPassword');
    }
}
