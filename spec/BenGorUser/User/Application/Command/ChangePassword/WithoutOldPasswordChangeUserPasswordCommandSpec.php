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

use BenGorUser\User\Application\Command\ChangePassword\WithoutOldPasswordChangeUserPasswordCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of WithoutOldPasswordChangeUserPasswordCommand class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class WithoutOldPasswordChangeUserPasswordCommandSpec extends ObjectBehavior
{
    function it_creates_a_command()
    {
        $this->beConstructedWith('bengor@user.com', 'newPassword');
        $this->shouldHaveType(WithoutOldPasswordChangeUserPasswordCommand::class);

        $this->email()->shouldReturn('bengor@user.com');
        $this->newPlainPassword()->shouldReturn('newPassword');
    }
}
