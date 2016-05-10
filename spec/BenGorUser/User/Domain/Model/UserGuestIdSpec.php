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

use BenGorUser\User\Domain\Model\UserGuestId;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of User Guest Id value object class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserGuestIdSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UserGuestId::class);
    }

    function it_constructs_with_null_id()
    {
        $this->id()->shouldNotBe(null);
    }

    function it_constructs_with_string_id()
    {
        $this->beConstructedWith('testId');
        $this->id()->shouldBe('testId');
    }

    function it_compares_ids()
    {
        $this->beConstructedWith('testId');

        $this->equals(new UserGuestId('testId'))->shouldBe(true);
    }

    function it_compares_different_ids()
    {
        $this->beConstructedWith('testId');

        $this->equals(new UserGuestId('notEqual'))->shouldBe(false);
    }

    function it_renders_string()
    {
        $this->beConstructedWith('testId');

        $this->__toString()->shouldBe('testId');
    }
}
