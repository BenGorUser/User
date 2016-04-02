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

use BenGor\User\Application\Service\SignUp\SignUpUserRequest;
use BenGor\User\Application\Service\SignUp\SpecificationWithConfirmationSignUpUser;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of specification with confirmation sign up user class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SpecificationWithConfirmationSignUpUserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SpecificationWithConfirmationSignUpUser::class);
    }

    function it_gets_email(SignUpUserRequest $request)
    {
        $request->email()->shouldBeCalled()->willReturn('user@user.com');

        $this->email($request)->shouldReturnAnInstanceOf(UserEmail::class);
    }

    function it_pre_persists(User $user)
    {
        $this->prePersist($user)->shouldReturn($user);
    }
}
