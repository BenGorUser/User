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
use PhpSpec\ObjectBehavior;

/**
 * Spec file of Role Id value object class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoleIdSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Domain\Model\RoleId');
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

        $this->equals(new RoleId('testId'))->shouldBe(true);
    }

    function it_compares_different_ids()
    {
        $this->beConstructedWith('testId');

        $this->equals(new RoleId('notEqual'))->shouldBe(false);
    }

    function it_renders_string()
    {
        $this->beConstructedWith('testId');

        $this->__toString()->shouldBe('testId');
    }
}
