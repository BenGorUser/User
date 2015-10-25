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

use BenGor\User\Application\Service\SignUpUserRequest;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\BenGor\User\Domain\Model\DummyUserPasswordEncoder;

/**
 * Spec file of sign up user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SignUpUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith(
            $repository,
            new DummyUserPasswordEncoder('encodedPassword')
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Application\Service\SignUpUserService');
    }

    function it_signs_the_user_up(UserRepository $repository)
    {
        $request = new SignUpUserRequest('user@user.com', 'plainPassword');

        $repository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn(null);
        $repository->nextIdentity()->shouldBeCalled()->willReturn(new UserId('id'));
        $repository->persist(Argument::type('BenGor\User\Domain\Model\User'))->shouldBeCalled();

        $this->execute($request);
    }

    function it_doesnt_sign_up_if_user_exists(UserRepository $repository, User $user)
    {
        $request = new SignUpUserRequest('user@user.com', 'plainPassword');

        $repository->userOfEmail(Argument::type('BenGor\User\Domain\Model\UserEmail'))
            ->shouldBeCalled()->willReturn($user);
        $repository->nextIdentity()->shouldNotBeCalled();
        $repository->persist(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserAlreadyExistException')->duringExecute($request);
    }
}
