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
 * Spec file of User Id value object class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserEmailSpec extends ObjectBehavior
{
    function it_constructs_with_valid_email()
    {
        $this->beConstructedWith('test@test.com');
        $this->shouldHaveType('BenGor\User\Domain\Model\UserEmail');

        $this->email()->shouldBe('test@test.com');
        $this->domain()->shouldBe('test.com');
        $this->localPart()->shouldBe('test');
        $this->__toString()->shouldBe('test@test.com');
    }

    function it_constructs_with_invalid_email()
    {
        $this->beConstructedWith('invalid string');

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserInvalidEmailException')->duringInstantiation();
    }
}
