<?php

/*
 * This file is part of the BenGorUser package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenGorUser\User\Domain\Model;

use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Infrastructure\Security\Test\DummyUserPasswordEncoder;
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
    function it_is_initializable()
    {
        $this->shouldHaveType(UserPassword::class);
    }

    function it_generates_from_encoded_password()
    {
        $encodedPassword = 'akjodsjfiqrfiheqiufhuieqwfjewi';
        $salt = 'fojewifjeiowjfiuoewjqiof';

        $this->beConstructedFromEncoded($encodedPassword, $salt);

        $this->encodedPassword()->shouldBe($encodedPassword);
        $this->salt()->shouldBe($salt);
    }

    function it_generates_from_plain_password(UserPasswordEncoder $encoder)
    {
        $plainPassword = 'secretPassword';
        $encodedPassword = 'iudfhiuewhfuiewhiufhewiufewhiufwe';

        $encoder->encode($plainPassword, Argument::type('string'))->shouldBecalled()->willReturn($encodedPassword);

        $this->beConstructedFromPlain($plainPassword, $encoder);

        $this->encodedPassword($encodedPassword)->shouldBe($encodedPassword);
        $this->salt()->shouldNotBe(null);
    }

    function it_compares_passwords()
    {
        $encoder = new DummyUserPasswordEncoder('ajdqwjnfewnewnfewkjqnfewkjn');
        $this->beConstructedFromEncoded('ajdqwjnfewnewnfewkjqnfewkjn', 'thisIsTheSalt');

        $this->equals(123456, $encoder)->shouldBe(true);
    }
}
