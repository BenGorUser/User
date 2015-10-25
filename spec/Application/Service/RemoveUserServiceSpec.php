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

use BenGor\User\Application\Service\RemoveUserRequest;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of RemoveUserService class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RemoveUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserPasswordEncoder $encoder)
    {
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Application\Service\RemoveUserService');
    }

    function it_implements_application_service()
    {
        $this->shouldHaveType('Ddd\Application\Service\ApplicationService');
    }

//    function it_does_not_execute_because_the_passwords_are_not_equals()
//    {
//        $this->id()->shouldReturn('a-plain-string-id');
//    }

    function it_executes(UserRepository $repository, User $user, UserPasswordEncoder $encoder)
    {
        $request = new RemoveUserRequest('a-plain-string-id', 'a-plain-user-password');
//        $request->id()->shouldBeCalled()->willReturn('a-plain-string-id');
//        $request->password()->shouldBeCalled()->willReturn('a-plain-user-password');
//
        $repository->userOfId(new UserId('a-plain-string-id'))->shouldBeCalled()->willReturn($user);
        $userPassword = new UserPassword('a-plain-user-password', null, 'dummy-salt');
        $user->password()->shouldBeCalled()->willReturn($userPassword);
        $userPassword->equals($userPassword)->shouldBeCalled()->willReturn(true);
        $repository->remove($user)->shouldBeCalled();

        $this->execute($request);
    }
}
