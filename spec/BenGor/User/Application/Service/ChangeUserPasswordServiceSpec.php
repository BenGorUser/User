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

use BenGor\User\Application\Service\ChangeUserPasswordRequest;
use BenGor\User\Application\Service\ChangeUserPasswordService;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of change user password service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $this->beConstructedWith($repository, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeUserPasswordService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_changes_password(UserRepository $repository, User $user)
    {
        $request = new ChangeUserPasswordRequest('id', 'newPassword', 'oldPassword');
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $oldPassword = UserPassword::fromPlain('oldPassword', $encoder, 'dummy-salt');

        $repository->userOfId(Argument::type(UserId::class))->shouldBeCalled()->willReturn($user);
        $user->password()->shouldBeCalled()->willReturn($oldPassword);
        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }
}
