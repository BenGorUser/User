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

namespace spec\BenGor\User\Application\Service\RequestRememberPasswordToken;

use BenGor\User\Application\Service\RequestRememberPasswordToken\RequestRememberPasswordTokenRequest;
use BenGor\User\Application\Service\RequestRememberPasswordToken\RequestRememberPasswordTokenService;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of request remember password token service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RequestRememberPasswordTokenServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestRememberPasswordTokenService::class);
    }

    function it_requests_change_password(
        RequestRememberPasswordTokenRequest $request,
        UserRepository $repository,
        User $user
    ) {
        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn($user);

        $user->rememberPassword()->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_request_change_password_because_user_does_not_exist(
        RequestRememberPasswordTokenRequest $request,
        UserRepository $repository
    ) {
        $request->email()->shouldBeCalled()->willReturn('user@user.com');
        $repository->userOfEmail(new UserEmail('user@user.com'))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(UserDoesNotExistException::class)->duringExecute($request);
    }
}
