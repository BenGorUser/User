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

namespace spec\BenGor\User\Application\Service\SignUp;

use BenGor\User\Application\Service\SignUp\ByInvitationSignUpUserSpecification;
use BenGor\User\Application\Service\SignUp\SignUpUserRequest;
use BenGor\User\Application\Service\SignUp\SignUpUserSpecification;
use BenGor\User\Domain\Model\Exception\UserGuestDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserGuest;
use BenGor\User\Domain\Model\UserGuestRepository;
use BenGor\User\Domain\Model\UserToken;
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
        SignUpUserRequest $request,
        UserGuestRepository $repository,
        UserGuest $userGuest
    ) {
        $request->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userGuestOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn($userGuest);

        $email = new UserEmail('user@user.com');
        $userGuest->email()->shouldBeCalled()->willReturn($email);
        $repository->remove($userGuest)->shouldBeCalled();

        $this->email($request)->shouldReturn($email);
    }

    function it_does_not_get_email_because_user_guest_does_not_exist(
        SignUpUserRequest $request,
        UserGuestRepository $repository
    ) {
        $request->invitationToken()->shouldBeCalled()->willReturn('invitation-token');
        $repository->userGuestOfInvitationToken(new UserToken('invitation-token'))
            ->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserGuestDoesNotExistException::class)->duringEmail($request);
    }

    function it_pre_persists(User $user)
    {
        $user->enableAccount()->shouldBeCalled();

        $this->prePersist($user)->shouldReturn($user);
    }
}
