<?php

namespace BenGor\User\Domain\Event;

use Ddd\Domain\DomainEvent;
use Ddd\Domain\DomainEventSubscriber;
use BenGor\User\Domain\Model\UserMailer;
use BenGor\User\Domain\Model\UserRegistered;

/**
 * User registered mailer subscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserRegisteredMailerSubscriber implements DomainEventSubscriber
{
    /**
     * The mailer.
     *
     * @var UserMailer
     */
    private $mailer;

    /**
     * Constructor.
     *
     * @param UserMailer $aMailer The mailer
     */
    public function __construct(UserMailer $aMailer)
    {
        $this->mailer = $aMailer;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($aDomainEvent)
    {
        $user = $aDomainEvent->user();

        $this->mailer->mail('Registered successfully', '???', $user->email(), '???');
    }

    /**
     * {@inheritdoc}
     */
    public function isSubscribedTo($aDomainEvent)
    {
        return $aDomainEvent instanceof UserRegistered;
    }
}
