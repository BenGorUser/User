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

namespace spec\BenGor\User\Application\Service;

use BenGor\User\Application\Service\LogOutUserRequest;
use BenGor\User\Application\Service\LogOutUserService;
use BenGor\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGor\User\Domain\Model\Exception\UserInactiveException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use Ddd\Application\Service\ApplicationService;
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
        $this->shouldHaveType(LogOutUserService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_logs_the_user_out(UserRepository $repository, User $user)
    {
        $request = new LogOutUserRequest('id');

        $user->isEnabled()->shouldBeCalled()->willReturn(true);
        $user->logout()->shouldBeCalled();

        $repository->userOfId(Argument::type(UserId::class))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_doesnt_logout_disabled_user(UserRepository $repository, User $user)
    {
        $request = new LogOutUserRequest('id');

        $user->isEnabled()->shouldBeCalled()->willReturn(false);
        $user->logout()->shouldNotBeCalled();

        $repository->userOfId(Argument::type(UserId::class))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow(UserInactiveException::class)->duringExecute($request);
    }

    function it_doesnt_logout_unknown_user(UserRepository $repository, User $user)
    {
        $request = new LogOutUserRequest('id');

        $user->isEnabled()->shouldNotBeCalled();
        $user->logout()->shouldNotBeCalled();

        $repository->userOfId(Argument::type(UserId::class))->shouldBeCalled()->willReturn(null);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow(UserDoesNotExistException::class)->duringExecute($request);
    }
}
