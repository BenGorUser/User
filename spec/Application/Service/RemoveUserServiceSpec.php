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

use BenGor\User\Application\Service\RemoveUserRequest;
use BenGor\User\Application\Service\RemoveUserService;
use BenGor\User\Domain\Model\Exception\UserPasswordInvalidException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of RemoveUserService class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class RemoveUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveUserService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldHaveType(ApplicationService::class);
    }

    function it_does_not_remove_user_password_do_not_match(UserRepository $repository, User $user)
    {
        $encoder = new DummyUserPasswordEncoder('passwordThatDoesntMatch');
        $request = new RemoveUserRequest('userID', 'plainPassword');
        $password = UserPassword::fromPlain('wrongPassword', $encoder);

        $user->password()->willReturn($password);
        $repository->userOfId(Argument::type(UserId::class))->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserPasswordInvalidException::class)->duringExecute($request);
    }

    function it_removes_user(UserRepository $repository, User $user)
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $request = new RemoveUserRequest('userID', 'plainPassword');
        $password = UserPassword::fromPlain('plainPassword', $encoder);

        $user->password()->willReturn($password);
        $repository->userOfId(Argument::type(UserId::class))->shouldBeCalled()->willReturn($user);
        $repository->remove($user)->shouldBeCalled();

        $this->execute($request);
    }
}
