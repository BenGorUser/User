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

namespace spec\BenGorUser\User\Domain\Event;

use BenGorUser\User\Domain\Event\UserEventSubscriber;
use BenGorUser\User\Domain\Event\UserRememberPasswordRequestedMailerSubscriber;
use BenGorUser\User\Domain\Model\Event\UserInvited;
use BenGorUser\User\Domain\Model\Event\UserRememberPasswordRequested;
use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestId;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserMailable;
use BenGorUser\User\Domain\Model\UserMailableFactory;
use BenGorUser\User\Domain\Model\UserMailer;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Domain\Model\UserUrlGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of UserRememberPasswordRequestedMailerSubscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserRememberPasswordRequestedMailerSubscriberSpec extends ObjectBehavior
{
    function let(UserMailer $mailer, UserMailableFactory $mailableFactory, UserUrlGenerator $urlGenerator)
    {
        $this->beConstructedWith($mailer, $mailableFactory, $urlGenerator, 'bengor_user_user_change_password');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserRememberPasswordRequestedMailerSubscriber::class);
    }

    function it_implements_user_event_subscriber()
    {
        $this->shouldImplement(UserEventSubscriber::class);
    }

    function it_handles(UserUrlGenerator $urlGenerator, UserMailableFactory $mailableFactory)
    {
        $user = new User(
            new UserId(),
            new UserEmail('bengor@user.com'),
            UserPassword::fromEncoded('endoced-password', 'salt'),
            [new UserRole('ROLE_USER')]
        );
        $user->rememberPassword();

        $domainEvent = new UserRememberPasswordRequested($user);
        $mailable = new UserMailable(
            new UserEmail('benatespina@gmail.com'),
            new UserEmail('bengor@user.com'),
            'Dummy mail',
            'Dummy mail body text'
        );

        $urlGenerator->generate(
            'bengor_user_user_change_password', Argument::type('array')
        )->shouldBeCalled()->willReturn('bengor.user.com/user/change-password');
        $mailableFactory->build(
            'bengor@user.com', Argument::type('array')
        )->shouldBeCalled()->willReturn($mailable);

        $this->handle($domainEvent);
    }

    function it_is_subscribe_to()
    {
        $rememberPasswordRequestedDomainEvent = new UserRememberPasswordRequested(
            new User(
                new UserId(),
                new UserEmail('bengor@user.com'),
                UserPassword::fromEncoded('endoced-password', 'salt'),
                [new UserRole('ROLE_USER')]
            )
        );
        $invitedDomainEvent = new UserInvited(
            new UserGuest(
                new UserGuestId(),
                new UserEmail('bengor@user.com')
            )
        );

        $this->isSubscribedTo($rememberPasswordRequestedDomainEvent)->shouldReturn(true);
        $this->isSubscribedTo($invitedDomainEvent)->shouldReturn(false);
    }

    function it_is_subscribe_to_with_user_class(
        UserMailer $mailer,
        UserMailableFactory $mailableFactory,
        UserUrlGenerator $urlGenerator
    ) {
        $this->beConstructedWith(
            $mailer,
            $mailableFactory,
            $urlGenerator,
            'bengor_user_user_change_password',
            UserGuest::class
        );

        $rememberPasswordRequestedDomainEvent = new UserRememberPasswordRequested(
            new User(
                new UserId(),
                new UserEmail('bengor@user.com'),
                UserPassword::fromEncoded('endoced-password', 'salt'),
                [new UserRole('ROLE_USER')]
            )
        );

        $this->isSubscribedTo($rememberPasswordRequestedDomainEvent)->shouldReturn(false);
    }
}
