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

namespace spec\BenGorUser\User\Application\Command\Invite;

use BenGorUser\User\Application\Command\Invite\ResendInvitationUserCommand;
use BenGorUser\User\Application\Command\Invite\ResendInvitationUserHandler;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of ResendInvitationUserHandler class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ResendInvitationUserHandlerSpec extends ObjectBehavior
{
    function let(UserRepository $userRepository)
    {
        $this->beConstructedWith($userRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResendInvitationUserHandler::class);
    }

    function it_resends_user_invitation(
        ResendInvitationUserCommand $command,
        UserRepository $userRepository,
        User $user
    ) {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');

        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn($user);
        $userRepository->persist($user)->shouldBeCalled();

        $this->__invoke($command);
    }

    function it_does_not_invite_when_the_user_does_not_exist(
        UserRepository $userRepository,
        ResendInvitationUserCommand $command
    ) {
        $command->email()->shouldBeCalled()->willReturn('user@user.com');
        $email = new UserEmail('user@user.com');
        $userRepository->userOfEmail($email)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->during__invoke($command);
    }
}
