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

use PhpSpec\ObjectBehavior;

/**
 * Spec file of user role value object class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserRoleSpec extends ObjectBehavior
{
    function it_constructs_with_valid_role()
    {
        $this->beConstructedWith('ROLE_USER');
        $this->shouldHaveType('BenGor\User\Domain\Model\UserRole');

        $this->role()->shouldBe('ROLE_USER');
        $this->__toString()->shouldBe('ROLE_USER');
    }
}
