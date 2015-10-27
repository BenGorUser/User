<?php

/*
 * This file is part of the User library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenGor\User\Application\Service;

use BenGor\User\Application\Service\RequestRememberPasswordTokenRequest;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use spec\BenGor\User\Domain\Model\DummyUserPasswordEncoder;

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
        $this->shouldHaveType('BenGor\User\Application\Service\RequestRememberPasswordTokenService');
    }

    function it_requests_change_password(UserRepository $repository, User $user)
    {
        $request = new RequestRememberPasswordTokenRequest('test@test.com');

        $repository->userOfEmail(new UserEmail($request->email()))->shouldBeCalled()->willReturn($user);

        $user->rememberPassword()->shouldBeCalled();
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_does_not_request_change_password_because_user_does_not_exist(UserRepository $repository)
    {
        $request = new RequestRememberPasswordTokenRequest('test@test.com');

        $repository->userOfEmail(new UserEmail($request->email()))->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new UserDoesNotExistException())->duringExecute($request);
    }
}
