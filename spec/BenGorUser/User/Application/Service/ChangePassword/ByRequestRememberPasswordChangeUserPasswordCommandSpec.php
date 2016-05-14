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

namespace spec\BenGorUser\User\Application\Service\ChangePassword;

use BenGorUser\User\Application\Service\ChangePassword\ByRequestRememberPasswordChangeUserPasswordCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of ByRequestRememberPasswordChangeUserPasswordCommand class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByRequestRememberPasswordChangeUserPasswordCommandSpec extends ObjectBehavior
{
    function it_creates_a_command()
    {
        $this->beConstructedWith('newPassword', 'remember-password-token');
        $this->shouldHaveType(ByRequestRememberPasswordChangeUserPasswordCommand::class);

        $this->newPlainPassword()->shouldReturn('newPassword');
        $this->rememberPasswordToken()->shouldReturn('remember-password-token');
    }
}
