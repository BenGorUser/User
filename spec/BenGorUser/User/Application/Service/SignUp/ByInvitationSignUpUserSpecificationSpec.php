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

namespace spec\BenGorUser\User\Application\Service\SignUp;

use BenGorUser\User\Application\Service\SignUp\ByInvitationSignUpUserSpecification;
use BenGorUser\User\Application\Service\SignUp\SignUpUserCommand;
use BenGorUser\User\Application\Service\SignUp\SignUpUserSpecification;
use BenGorUser\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserToken;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of by invitation sign up user specification class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ByInvitationSignUpUserSpecificationSpec extends ObjectBehavior
{
    function let(UserGuestRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ByInvitationSignUpUserSpecification::class);
    }

    function it_implements_sign_up_user_specification()
    {
        $this->shouldImplement(SignUpUserSpecification::class);
    }

    function it_gets_email(
        SignUpUserCommand $command,
        UserGuestRepository $repository,
        UserGuest $userGuest
    ) {
        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userGuestOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn($userGuest);

        $email = new UserEmail('user@user.com');
        $userGuest->email()->shouldBeCalled()->willReturn($email);
        $repository->remove($userGuest)->shouldBeCalled();

        $this->email($command)->shouldReturn($email);
    }

    function it_does_not_get_email_because_user_guest_does_not_exist(
        SignUpUserCommand $command,
        UserGuestRepository $repository
    ) {
        $command->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userGuestOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserGuestDoesNotExistException::class)->duringEmail($command);
    }

    function it_pre_persists(User $user)
    {
        $user->enableAccount()->shouldBeCalled();

        $this->prePersist($user)->shouldReturn($user);
    }
}
