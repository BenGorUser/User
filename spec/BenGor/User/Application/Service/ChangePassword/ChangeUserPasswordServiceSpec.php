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

namespace spec\BenGor\User\Application\Service\ChangePassword;

use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordRequest;
use BenGor\User\Application\Service\ChangePassword\ChangeUserPasswordService;
use BenGor\User\Application\Service\ChangePassword\SpecificationChangeUserPassword;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use BenGor\User\Domain\Model\UserRepository;
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
    function let(
        UserRepository $repository,
        SpecificationChangeUserPassword $specification,
        UserPasswordEncoder $encoder
    ) {
        $this->beConstructedWith($repository, $encoder, $specification);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeUserPasswordService::class);
    }

    function it_changes_password(
        ChangeUserPasswordRequest $request,
        SpecificationChangeUserPassword $specification,
        UserRepository $repository,
        User $user
    ) {
        $specification->user($request)->shouldBeCalled()->willReturn($user);
        $user->changePassword(Argument::type(UserPassword::class))->shouldBeCalled();

        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }
}
