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

namespace spec\BenGorUser\User\Application\Command\PurgeOutdatedTokens;

use BenGorUser\User\Application\Command\PurgeOutdatedTokens\PurgeOutdatedRememberPasswordTokensUserCommand;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of PurgeOutdatedIRememberPasswordTokensCommand class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class PurgeOutdatedRememberPasswordTokensUserCommandSpec extends ObjectBehavior
{
    function it_creates_request()
    {
        $this->beConstructedWith();
        $this->shouldHaveType(PurgeOutdatedRememberPasswordTokensUserCommand::class);
    }
}
