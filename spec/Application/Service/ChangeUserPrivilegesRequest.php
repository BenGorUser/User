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

use PhpSpec\ObjectBehavior;

/**
 * Spec file of change user privileges request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ChangeUserPrivilegesRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('user-id', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->shouldHaveType('BenGor\User\Application\Service\ChangeUserPrivilegesRequest');

        $this->id()->shouldReturn('user-id');
        $this->roles()->shouldReturn(['ROLE_USER', 'ROLE_ADMIN']);
    }
}
