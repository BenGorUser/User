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

namespace spec\BenGor\User\Application\Service;

use PhpSpec\ObjectBehavior;

/**
 * Spec file of change user password request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordRequestSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith('id', 'newPassword', 'oldPassword');
        $this->shouldHaveType('BenGor\User\Application\Service\ChangeUserPasswordRequest');

        $this->id()->shouldBe('id');
        $this->newPlainPassword()->shouldBe('newPassword');
        $this->oldPlainPassword()->shouldBe('oldPassword');
    }
}
