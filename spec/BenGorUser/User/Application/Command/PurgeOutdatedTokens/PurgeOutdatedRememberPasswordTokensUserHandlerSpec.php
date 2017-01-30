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
use BenGorUser\User\Application\Command\PurgeOutdatedTokens\PurgeOutdatedRememberPasswordTokensUserHandler;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of PurgeOutdatedRememberPasswordTokensHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class PurgeOutdatedRememberPasswordTokensUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PurgeOutdatedRememberPasswordTokensUserHandler::class);
    }

    function it_purges_outdated_remember_password_tokens(
        UserRepository $repository,
        PurgeOutdatedRememberPasswordTokensUserCommand $command,
        User $user
    ) {
        $repository->all()->shouldBeCalled()->willReturn([$user]);

        $user->isRememberPasswordTokenExpired()->shouldBeCalled()->willReturn(true);
        $user->cleanRememberPasswordToken()->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }
}
