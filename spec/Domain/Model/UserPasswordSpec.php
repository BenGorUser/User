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

use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserPasswordEncoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of User Id value object class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserPasswordSpec extends ObjectBehavior
{
    function it_generates_encrypted_pass(UserPasswordEncoder $encoder)
    {
        $plainPassword = 'securePassword';
        $encodedPassword = 'ajdqwjnfewnewnfewkjqnfewkjn';

        $encoder->encode($plainPassword, Argument::type('string'))
            ->willReturn($encodedPassword);

        $this->beConstructedWith($plainPassword, $encoder);

        $this->shouldHaveType('BenGor\User\Domain\Model\UserPassword');
        $this->encodedPassword()->shouldBe($encodedPassword);
    }

    function it_requieres_encoder_when_not_salt_given()
    {
        $this->beConstructedWith('securePassword');

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserPasswordEncoderRequiredException')
            ->during('__construct', ['securePassword']);
    }

    function it_assumes_password_is_already_encoded_when_salt_given()
    {
        $encodedPassword = 'ajdqwjnfewnewnfewkjqnfewkjn';

        $this->beConstructedWith($encodedPassword, null, 'thisIsTheSalt');

        $this->encodedPassword()->shouldBe($encodedPassword);
        $this->salt()->shouldBe('thisIsTheSalt');
    }

    function it_compares_passwords(UserPasswordEncoder $encoder) {
        $plainPassword = 'securePassword';
        $encodedPassword = 'ajdqwjnfewnewnfewkjqnfewkjn';

        $encoder->encode($plainPassword, Argument::type('string'))
            ->willReturn($encodedPassword);

        $this->beConstructedWith($plainPassword, $encoder);

        $this->equals(new UserPassword($encodedPassword, null, 'thisIsTheSalt'))->shouldBe(true);
    }
}
