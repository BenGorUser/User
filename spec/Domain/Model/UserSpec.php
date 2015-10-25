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

namespace spec\BenGor\User\Domain\Model;

use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of User domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserSpec extends ObjectBehavior
{
    function let(UserPasswordEncoder $encoder)
    {
        $this->beConstructedThrough('register', [
            new UserId(),
            new UserEmail('test@test.com'),
            'strongpassword',
            $encoder
        ]);

    }
    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Domain\Model\User');
    }

    function it_registers_a_user()
    {
        $this->id()->id()->shouldNotBe(null);
        $this->email()->email()->shouldBe('test@test.com');
        $this->confirmationToken()->token()->shouldNotBe(null);
        $this->isEnabled()->shouldBe(false);
    }

    function it_enables_an_account(UserPasswordEncoder $encoder)
    {
        $this->isEnabled()->shouldBe(false);

        $this->enableAccount();

        $this->isEnabled()->shouldBe(true);
    }

    function it_logs_in_user(UserPasswordEncoder $encoder)
    {
        $this->lastLogin()->shouldBe(null);

        $this->login();

        $this->lastLogin()->shouldReturnAnInstanceOf('\Datetime');
    }

    function it_remembers_password(UserPasswordEncoder $encoder)
    {
        $token = $this->confirmationToken();

        $this->rememberPassword();

        $this->confirmationToken()->shouldNotBe($token);
    }
}
