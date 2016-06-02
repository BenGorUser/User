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

use BenGorUser\User\Application\Query\UserOfInvitationTokenQuery;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserOfInvitationTokenQuery class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfInvitationTokenQuerySpec extends ObjectBehavior
{
    function it_creates_a_query()
    {
        $this->beConstructedWith('invitation-token');
        $this->shouldHaveType(UserOfInvitationTokenQuery::class);
        $this->invitationToken()->shouldReturn('invitation-token');
    }

    function it_cannot_creates_a_query_with_null_token()
    {
        $this->beConstructedWith(null);
        $this->shouldThrow(new \InvalidArgumentException('Invitation token cannot be null'))->duringInstantiation();
    }
}
