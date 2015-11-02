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

        $encoder->encode($plainPassword, Argument::type('string'))->willReturn($encodedPassword);

        $this->beConstructedFromPlain($plainPassword, $encoder);

        $this->encodedPassword($encodedPassword)->shouldBe($encodedPassword);
        $this->salt()->shouldNotBe(null);
    }

    function it_compares_passwords()
    {
        $encodedPassword = 'ajdqwjnfewnewnfewkjqnfewkjn';

        $this->beConstructedFromEncoded($encodedPassword, 'thisIsTheSalt');

        $this->equals(UserPassword::fromEncoded($encodedPassword, 'thisIsTheSalt'))->shouldBe(true);
    }
}
