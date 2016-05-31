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

namespace spec\BenGorUser\User\Application\Query;

use BenGorUser\User\Application\Query\UserOfEmailQuery;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserOfEmailQuery class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfEmailQuerySpec extends ObjectBehavior
{
    function it_creates_a_query()
    {
        $this->beConstructedWith('bengor@user.com');
        $this->shouldHaveType(UserOfEmailQuery::class);
        $this->email()->shouldReturn('bengor@user.com');
    }
}
