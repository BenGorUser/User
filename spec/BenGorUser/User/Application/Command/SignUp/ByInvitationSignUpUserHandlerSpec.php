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

namespace spec\BenGorUser\User\Application\Command\SignUp;

use BenGorUser\User\Application\Command\SignUp\ByInvitationSignUpUserCommand;
use BenGorUser\User\Application\Command\SignUp\ByInvitationSignUpUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of ByInvitationSignUpUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationSignUpUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encoded-password')
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ByInvitationSignUpUserHandler::class);
    }

    function it_signs_the_user_up(
        ByInvitationSignUpUserCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn($user);

        $command->password()->shouldBeCalled()->willReturn('plain-password');
        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();
        $user->enableAccount()->shouldBeCalled();
        $user->acceptInvitation()->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_sign_up_because_user_does_not_exist(
        ByInvitationSignUpUserCommand $command,
        UserRepository $repository
    ) {
        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
