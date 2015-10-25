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
use Prophecy\Argument;
use spec\BenGor\User\Domain\Model\DummyUserPasswordEncoder;

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
        $this->shouldHaveType('BenGor\User\Application\Service\RemoveUserService');
    }

    function it_implements_application_service()
    {
        $this->shouldHaveType('Ddd\Application\Service\ApplicationService');
    }

    function it_does_not_remove_user_password_do_not_match(UserRepository $repository, User $user)
    {
        $encoder = new DummyUserPasswordEncoder('passwordThatDoesntMatch');
        $request = new RemoveUserRequest('userID', 'plainPassword');
        $password = UserPassword::fromPlain('wrongPassword', $encoder);

        $user->password()->willReturn($password);
        $repository->userOfId(Argument::type('BenGor\User\Domain\Model\UserId'))
            ->willReturn($user);

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserInvalidPasswordException')
            ->duringExecute($request);
    }

    function it_removes_user(UserRepository $repository, User $user)
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $request = new RemoveUserRequest('userID', 'plainPassword');
        $password = UserPassword::fromPlain('plainPassword', $encoder);

        $user->password()->willReturn($password);
        $repository->userOfId(Argument::type('BenGor\User\Domain\Model\UserId'))
            ->willReturn($user);
        $repository->remove($user)->shouldBeCalled();

        $this->execute($request);
    }
}
