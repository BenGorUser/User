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

use BenGor\User\Application\DataTransformer\UserDataTransformer;
use BenGor\User\Application\Service\SignUp\SignUpUserRequest;
use BenGor\User\Application\Service\SignUp\SignUpUserService;
use BenGor\User\Application\Service\SignUp\SpecificationSignUpUser;
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
    function let(
        UserRepository $repository,
        UserFactory $factory,
        UserDataTransformer $dataTransformer,
        SpecificationSignUpUser $specification
    ) {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encoded-password'),
            $factory,
            $dataTransformer,
            $specification
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SignUpUserService::class);
    }

    function it_signs_the_user_up(
        SignUpUserRequest $request,
        UserRepository $repository,
        UserFactory $factory,
        SpecificationSignUpUser $specification,
        UserDataTransformer $dataTransformer,
        User $user,
        \DateTimeImmutable $createdOn,
        \DateTimeImmutable $lastLogin,
        \DateTimeImmutable $updatedOn
    ) {
        $email = new UserEmail('user@user.com');
        $specification->email($request)->shouldBeCalled()->willReturn($email);

        $repository->userOfEmail($email)->shouldBeCalled()->willReturn(null);

        $id = new UserId('user-id');
        $repository->nextIdentity()->shouldBeCalled()->willReturn($id);

        $request->password()->shouldBeCalled()->willReturn('plain-password');

        $request->roles()->shouldBeCalled()->willReturn(['ROLE_USER']);
        $roles = [new UserRole('ROLE_USER')];

        $factory->register($id, $email, Argument::type(UserPassword::class), $roles)->shouldBeCalled()->willReturn($user);
        $specification->prePersist($user)->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();
        $dataTransformer->write($user)->shouldBeCalled();
        $dataTransformer->read()->shouldBeCalled()->willReturn([
            'id'                      => 'user-id',
            'confirmation_token'      => null,
            'created_on'              => $createdOn,
            'email'                   => 'user@user.com',
            'last_login'              => $lastLogin,
            'password'                => 'encoded-password',
            'remember_password_token' => null,
            'roles'                   => ['ROLE_USER'],
            'updated_on'              => $updatedOn,
        ]);

        $this->execute($request);
    }

    function it_does_not_sign_up_if_user_exists(
        SignUpUserRequest $request,
        UserRepository $repository,
        SpecificationSignUpUser $specification,
        User $user
    ) {
        $email = new UserEmail('user@user.com');
        $specification->email($request)->shouldBeCalled()->willReturn($email);
        $repository->userOfEmail($email)->shouldBeCalled()->willReturn($user);

        $this->shouldThrow(UserAlreadyExistException::class)->duringExecute($request);
    }
}
