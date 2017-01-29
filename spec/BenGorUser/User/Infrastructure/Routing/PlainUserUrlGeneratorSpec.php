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

namespace spec\BenGorUser\User\Infrastructure\Routing;

use BenGorUser\User\Domain\Model\UserUrlGenerator;
use BenGorUser\User\Infrastructure\Routing\PlainUserUrlGenerator;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of PlainUserUrlGenerator class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class PlainUserUrlGeneratorSpec extends ObjectBehavior
{
    function it_builds()
    {
        $this->beConstructedWith('http://bengoruser.com?token={token}');
        $this->shouldHaveType(PlainUserUrlGenerator::class);
        $this->shouldImplement(UserUrlGenerator::class);

        $this->generate('awesome-token')->shouldReturn('http://bengoruser.com?token=awesome-token');
    }
}
