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

use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestId;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserGuest class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserGuestSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            new UserGuestId(),
            new UserEmail('test@test.com')
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserGuest::class);
    }

    function it_creates_a_user_guest()
    {
        $this->id()->id()->shouldNotBe(null);
        $this->email()->email()->shouldBe('test@test.com');
        $this->invitationToken()->token()->shouldNotBe(null);
    }

    function it_regenerates_invitation_token()
    {
        $token = $this->invitationToken();
        $this->regenerateInvitationToken();
        $this->invitationToken()->shouldNotBe($token);
    }
}
