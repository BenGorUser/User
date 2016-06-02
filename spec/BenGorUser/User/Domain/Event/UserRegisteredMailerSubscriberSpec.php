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
use BenGorUser\User\Domain\Event\UserRegisteredMailerSubscriber;
use BenGorUser\User\Domain\Model\Event\UserInvited;
use BenGorUser\User\Domain\Model\Event\UserRegistered;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserMailable;
use BenGorUser\User\Domain\Model\UserMailableFactory;
use BenGorUser\User\Domain\Model\UserMailer;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Domain\Model\UserUrlGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of UserRegisteredMailerSubscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserRegisteredMailerSubscriberSpec extends ObjectBehavior
{
    function let(UserMailer $mailer, UserMailableFactory $mailableFactory, UserUrlGenerator $urlGenerator)
    {
        $this->beConstructedWith($mailer, $mailableFactory, $urlGenerator, 'bengor_user_user_enable');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserRegisteredMailerSubscriber::class);
    }

    function it_implements_user_event_subscriber()
    {
        $this->shouldImplement(UserEventSubscriber::class);
    }

    function it_handles(UserUrlGenerator $urlGenerator, UserMailableFactory $mailableFactory)
    {
        $domainEvent = new UserRegistered(
            new UserId(),
            new UserEmail('bengor@user.com'),
            new UserToken('confirmation-token')
        );
        $mailable = new UserMailable(
            new UserEmail('benatespina@gmail.com'),
            new UserEmail('bengor@user.com'),
            'Dummy mail',
            'Dummy mail body text'
        );

        $urlGenerator->generate(
            'bengor_user_user_enable', Argument::type('array')
        )->shouldBeCalled()->willReturn('bengor.user.com/user/enable');
        $mailableFactory->build(
            'bengor@user.com', Argument::type('array')
        )->shouldBeCalled()->willReturn($mailable);

        $this->handle($domainEvent);
    }

    function it_is_subscribe_to()
    {
        $registeredDomainEvent = new UserRegistered(
            new UserId(),
            new UserEmail('bengor@user.com'),
            new UserToken('confirmation-token')
        );
        $invitedDomainEvent = new UserInvited(
            new UserId(),
            new UserEmail('bengor@user.com'),
            new UserToken('invitation-token')
        );

        $this->isSubscribedTo($registeredDomainEvent)->shouldReturn(true);
        $this->isSubscribedTo($invitedDomainEvent)->shouldReturn(false);
    }
}
