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

use BenGorUser\User\Application\Command\SignUp\ByInvitationWithConfirmationSignUpUserCommand;
use BenGorUser\User\Application\Command\SignUp\ByInvitationWithConfirmationSignUpUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of ByInvitationWithConfirmationSignUpUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationWithConfirmationSignUpUserHandlerSpec extends ObjectBehavior
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
        $this->shouldHaveType(ByInvitationWithConfirmationSignUpUserHandler::class);
    }

    function it_signs_the_user_up(
        ByInvitationWithConfirmationSignUpUserCommand $command,
        UserRepository $repository,
        User $user
    ) {
        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn($user);

        $command->password()->shouldBeCalled()->willReturn('plain-password');
        $command->roles()->shouldBeCalled()->willReturn(['ROLE_USER']);
        $user->grant(Argument::type(UserRole::class))->shouldBeCalled();
        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_sign_up_because_user_does_not_exist(
        ByInvitationWithConfirmationSignUpUserCommand $command,
        UserRepository $repository
    ) {
        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
