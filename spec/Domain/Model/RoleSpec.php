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

namespace spec\BenGor\User\Domain\Model;

use BenGor\User\Domain\Model\RoleId;
use BenGor\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of Role entity class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoleSpec extends ObjectBehavior
{
    function it_creates_role()
    {
        $userRole = new UserRole('ROLE_USER');
        $this->beConstructedWith(
            new RoleId(),
            $userRole
        );

        $this->shouldHaveType('BenGor\User\Domain\Model\Role');

        $this->id()->id()->shouldNotBe(null);
        $this->role()->shouldReturn($userRole);
    }
}
