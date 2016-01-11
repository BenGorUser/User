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

use BenGor\User\Application\Service\SignUpUserRequest;
use BenGor\User\Application\Service\SignUpUserResponse;
use BenGor\User\Application\Service\SignUpUserService;
use BenGor\User\Domain\Model\Exception\UserAlreadyExistException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserFactory;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of sign up user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SignUpUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository, UserFactory $factory)
    {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encodedPassword'),
            $factory
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SignUpUserService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement('Ddd\Application\Service\ApplicationService');
    }

    function it_signs_the_user_up(UserRepository $repository, UserFactory $factory, User $user)
    {
        $request = new SignUpUserRequest('user@user.com', 'plainPassword', ['ROLE_USER']);
        $email = new UserEmail('user@user.com');
        $id = new UserId('id');
        $roles = [new UserRole('ROLE_USER')];

        $repository->userOfEmail($email)->shouldBeCalled()->willReturn(null);
        $repository->nextIdentity()->shouldBeCalled()->willReturn($id);

        $factory->register(
            $id, $email, Argument::type(UserPassword::class), $roles
        )->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request)->shouldReturnAnInstanceOf(SignUpUserResponse::class);
    }

    function it_does_not_sign_up_if_user_exists(UserRepository $repository, User $user)
    {
        $request = new SignUpUserRequest('user@user.com', 'plainPassword', ['ROLE_USER']);

        $repository->userOfEmail(Argument::type(UserEmail::class))->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->duringExecute($request);
    }
}
