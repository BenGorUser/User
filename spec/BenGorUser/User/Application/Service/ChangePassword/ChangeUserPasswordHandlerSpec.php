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

namespace spec\BenGorUser\User\Application\Service\ChangePassword;

use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordCommand;
use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordHandler;
use BenGorUser\User\Application\Service\ChangePassword\ChangeUserPasswordSpecification;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of change user password service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordHandlerSpec extends ObjectBehavior
{
    function let(
        UserRepository $repository,
        ChangeUserPasswordSpecification $specification,
        UserPasswordEncoder $encoder
    ) {
        $this->beConstructedWith($repository, $encoder, $specification);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeUserPasswordHandler::class);
    }

    function it_changes_password(
        ChangeUserPasswordCommand $command,
        ChangeUserPasswordSpecification $specification,
        UserRepository $repository,
        User $user
    ) {
        $specification->user($command)->shouldBeCalled()->willReturn($user);
        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }
}
