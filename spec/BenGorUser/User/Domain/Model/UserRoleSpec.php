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

namespace spec\BenGorUser\User\Domain\Model;

use BenGorUser\User\Domain\Model\Exception\UserRoleInvalidException;
use BenGorUser\User\Domain\Model\UserRole;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserRole class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserRoleSpec extends ObjectBehavior
{
    function it_constructs_with_valid_role()
    {
        $this->beConstructedWith('ROLE_USER');
        $this->shouldHaveType(UserRole::class);

        $this->role()->shouldBe('ROLE_USER');
        $this->__toString()->shouldBe('ROLE_USER');
    }

    function it_constructs_with_null()
    {
        $this->beConstructedWith(null);

        $this->shouldThrow(UserRoleInvalidException::class)->duringInstantiation();
    }

    function it_should_only_construct_with_string()
    {
        $this->beConstructedWith(new UserRole('asdasd'));
        $this->shouldThrow(UserRoleInvalidException::class)->duringInstantiation();
    }
}
