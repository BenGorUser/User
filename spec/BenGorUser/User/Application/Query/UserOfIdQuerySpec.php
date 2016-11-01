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

use BenGorUser\User\Application\Query\UserOfIdQuery;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserOfIdQuery class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfIdQuerySpec extends ObjectBehavior
{
    function it_creates_a_query()
    {
        $this->beConstructedWith('user-id');
        $this->shouldHaveType(UserOfIdQuery::class);
        $this->id()->shouldReturn('user-id');
    }

    function it_cannot_creates_a_query_with_null_id()
    {
        $this->beConstructedWith(null);
        $this->shouldThrow(new \InvalidArgumentException('Id cannot be null'))->duringInstantiation();
    }
}
