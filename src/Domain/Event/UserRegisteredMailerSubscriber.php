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

namespace BenGor\User\Domain\Event;

use BenGor\User\Domain\Model\UserMailer;
use BenGor\User\Domain\Model\UserRegistered;
use Ddd\Domain\DomainEventSubscriber;

/**
 * User registered mailer subscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserRegisteredMailerSubscriber implements DomainEventSubscriber
{
    /**
     * The body of email.
     *
     * @var string
     */
    private $body;

    /**
     * The sender email.
     *
     * @var string
     */
    private $fromEmail;

    /**
     * The mailer.
     *
     * @var UserMailer
     */
    private $mailer;

    /**
     * The subject of the email.
     *
     * @var string
     */
    private $subject;

    /**
     * Constructor.
     *
     * @param UserMailer $aMailer    The mailer
     * @param string     $aFromEmail The sender email
     * @param string     $aBody      The body of email
     * @param string     $aSubject   The subject of email, by default is "Registered successfully"
     */
    public function __construct(UserMailer $aMailer, $aFromEmail, $aBody, $aSubject = 'Registered successfully')
    {
        $this->mailer = $aMailer;
        $this->fromEmail = $aFromEmail;
        $this->body = $aBody;
        $this->subject = $aSubject;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($aDomainEvent)
    {
        $user = $aDomainEvent->user();

        $this->mailer->mail($this->subject, $this->fromEmail, $user->email(), $this->body);
    }

    /**
     * {@inheritdoc}
     */
    public function isSubscribedTo($aDomainEvent)
    {
        return $aDomainEvent instanceof UserRegistered;
    }
}
