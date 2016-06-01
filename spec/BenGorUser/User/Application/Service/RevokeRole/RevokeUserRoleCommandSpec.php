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

namespace spec\BenGorUser\User\Application\Command\RevokeRole;

use BenGorUser\User\Application\Command\RevokeRole\RevokeUserRoleCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of revoke user role request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RevokeUserRoleCommandSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('user-id', ['ROLE_USER']);
        $this->shouldHaveType(RevokeUserRoleCommand::class);

        $this->id()->shouldReturn('user-id');
        $this->role()->shouldReturn(['ROLE_USER']);
    }
}
