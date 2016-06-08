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

use BenGorUser\User\Domain\Model\Exception\UserEmailInvalidException;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserMailable;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserMailable class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class UserMailableSpec extends ObjectBehavior
{
    function it_creates_user_mailable()
    {
        $from = new UserEmail('bengor@user.com');
        $to = new UserEmail('bengor@user.com');

        $this->beConstructedWith(
            $to,
            $from,
            'The email subject',
            'The email content',
            '<html>The email content</html>'
        );
        $this->shouldHaveType(UserMailable::class);

        $this->to()->shouldReturn($to);
        $this->from()->shouldReturn($from);
        $this->subject()->shouldReturn('The email subject');
        $this->bodyText()->shouldReturn('The email content');
        $this->bodyHtml()->shouldReturn('<html>The email content</html>');
    }

    function it_creates_user_mailable_with_multiple_receivers()
    {
        $from = new UserEmail('bengor@user.com');
        $to = [
            new UserEmail('gorka.lauzirika@gmail.com'),
            new UserEmail('benatespina@gmail.com'),
            new UserEmail('bengor@user.com'),
        ];

        $this->beConstructedWith(
            $to,
            $from,
            'The email subject',
            'The email content',
            '<html>The email content</html>'
        );
        $this->shouldHaveType(UserMailable::class);

        $this->to()->shouldReturn($to);
        $this->from()->shouldReturn($from);
        $this->subject()->shouldReturn('The email subject');
        $this->bodyText()->shouldReturn('The email content');
        $this->bodyHtml()->shouldReturn('<html>The email content</html>');
    }

    function it_creates_user_mailable_with_invalid_email()
    {
        $from = new UserEmail('bengor@user.com');
        $to = 'not-user-email-instance';

        $this->beConstructedWith(
            $to,
            $from,
            'The email subject',
            'The email content',
            '<html>The email content</html>'
        );

        $this->shouldThrow(UserEmailInvalidException::class)->duringInstantiation();
    }
}
