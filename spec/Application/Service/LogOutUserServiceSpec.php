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

use BenGor\User\Application\Service\LogOutUserRequest;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of logout user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class LogOutUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Application\Service\LogOutUserService');
    }

    function it_logs_the_user_out(UserRepository $repository, User $user)
    {
        $request = new LogOutUserRequest('id');

        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->logout()->shouldBeCalled();

        $repository->userOfId(Argument::type('BenGor\User\Domain\Model\UserId'))
            ->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_doesnt_logout_disabled_user(UserRepository $repository, User $user)
    {
        $request = new LogOutUserRequest('id');

        $user->isEnabled()->shouldBeCalled()->willReturn(false);
        $user->logout()->shouldNotBeCalled();

        $repository->userOfId(Argument::type('BenGor\User\Domain\Model\UserId'))
            ->willReturn($user);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserInactiveException')
            ->duringExecute($request);
    }

    function it_doesnt_logout_unknown_user(UserRepository $repository, User $user)
    {
        $request = new LogOutUserRequest('id');

        $user->isEnabled()->shouldNotBeCalled();
        $user->logout()->shouldNotBeCalled();

        $repository->userOfId(Argument::type('BenGor\User\Domain\Model\UserId'))
            ->willReturn(null);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserDoesNotExistException')
            ->duringExecute($request);
    }
}
