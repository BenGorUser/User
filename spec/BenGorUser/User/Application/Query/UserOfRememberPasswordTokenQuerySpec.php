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

use BenGorUser\User\Application\Query\UserOfRememberPasswordTokenQuery;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserOfRememberTokenQuery class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfRememberPasswordTokenQuerySpec extends ObjectBehavior
{
    function it_creates_a_query()
    {
        $this->beConstructedWith('remember-password-token');
        $this->shouldHaveType(UserOfRememberPasswordTokenQuery::class);
        $this->rememberPasswordToken()->shouldReturn('remember-password-token');
    }

    function it_cannot_creates_a_query_with_null_token()
    {
        $this->beConstructedWith(null);
        $this->shouldThrow(
            new \InvalidArgumentException('Remember password token cannot be null')
        )->duringInstantiation();
    }
}
