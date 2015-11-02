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

namespace spec\BenGor\User\Domain\Model;

use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of User domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserSpec extends ObjectBehavior
{
    function let()
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');

        $this->beConstructedWith(
            new UserId(),
            new UserEmail('test@test.com'),
            UserPassword::fromPlain('strongpassword', $encoder)
        );
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

    function it_enables_an_account()
    {
        $this->isEnabled()->shouldBe(false);
        $this->confirmationToken()->shouldReturnAnInstanceOf('BenGor\User\Domain\Model\UserToken');
        $this->enableAccount();
        $this->isEnabled()->shouldBe(true);
        $this->confirmationToken()->shouldReturn(null);
    }

    function it_logs_in_user()
    {
        $this->lastLogin()->shouldReturn(null);
        $this->login();
        $this->lastLogin()->shouldReturnAnInstanceOf('\Datetime');
    }

    function it_remembers_password()
    {
        $this->rememberPasswordToken()->shouldReturn(null);
        $this->rememberPassword();
        $this->rememberPasswordToken()->shouldReturnAnInstanceOf('BenGor\User\Domain\Model\UserToken');
    }

    function it_changes_password()
    {
        $encoder = new DummyUserPasswordEncoder('encodedPassword');
        $newPassword = UserPassword::fromPlain('strongnewpassword', $encoder);

        $this->rememberPassword();
        $this->rememberPasswordToken()->shouldReturnAnInstanceOf('BenGor\User\Domain\Model\UserToken');
        $this->changePassword($this->password(), $newPassword);
        $this->rememberPasswordToken()->shouldReturn(null);
        $this->password()->shouldReturn($newPassword);
    }
}
