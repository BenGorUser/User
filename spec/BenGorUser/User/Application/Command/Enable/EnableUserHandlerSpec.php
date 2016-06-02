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

namespace spec\BenGorUser\User\Application\Command\Enable;

use BenGorUser\User\Application\Command\Enable\EnableUserCommand;
use BenGorUser\User\Application\Command\Enable\EnableUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of enable user Command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class EnableUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EnableUserHandler::class);
    }

    function it_activates_user(UserRepository $repository, User $user, EnableUserCommand $command)
    {
        $command->confirmationToken()->shouldBeCalled()->willReturn('confirmation-token');
        $user->enableAccount()->shouldBeCalled();
        $repository->userOfConfirmationToken(new UserToken('confirmation-token'))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_doesnt_activate_user_with_wrong_token(
        UserRepository $repository,
        User $user,
        EnableUserCommand $command
    ) {
        $command->confirmationToken()->shouldBeCalled()->willReturn('confirmation-token');
        $user->enableAccount()->shouldNotBeCalled();
        $repository->userOfConfirmationToken(new UserToken('confirmation-token'))->shouldBeCalled()->willReturn(null);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow(UserTokenNotFoundException::class)->during__invoke($command);
    }
}
