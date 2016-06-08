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

use BenGorUser\User\Domain\Model\Exception\UserEmailInvalidException;
use BenGorUser\User\Domain\Model\UserEmail;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserEmail class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserEmailSpec extends ObjectBehavior
{
    function it_constructs_with_valid_email()
    {
        $this->beConstructedWith('bengor@user.com');
        $this->shouldHaveType(UserEmail::class);

        $this->email()->shouldBe('bengor@user.com');
        $this->domain()->shouldBe('user.com');
        $this->localPart()->shouldBe('bengor');
        $this->__toString()->shouldBe('bengor@user.com');
    }

    function it_constructs_with_invalid_email()
    {
        $this->beConstructedWith('invalid string');

        $this->shouldThrow(UserEmailInvalidException::class)->duringInstantiation();
    }

    function it_compares_ids()
    {
        $this->beConstructedWith('bengor@user.com');

        $this->equals(new UserEmail('bengor@user.com'))->shouldBe(true);
    }

    function it_compares_different_ids()
    {
        $this->beConstructedWith('bengor@user.com');

        $this->equals(new UserEmail('not-bengor@user.com'))->shouldBe(false);
    }
}
