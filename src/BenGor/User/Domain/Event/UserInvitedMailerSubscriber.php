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

namespace BenGor\User\Domain\Event;

use BenGor\User\Domain\Model\Event\UserInvited;
use BenGor\User\Domain\Model\UserMailableFactory;
use BenGor\User\Domain\Model\UserMailer;
use BenGor\User\Domain\Model\UserUrlGenerator;
use Ddd\Domain\DomainEventSubscriber;

/**
 * User invited mailer subscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserInvitedMailerSubscriber implements DomainEventSubscriber
{
    /**
     * The fully qualified user class name.
     *
     * @var string|null
     */
    private $userClass;

    /**
     * The mailable factory.
     *
     * @var UserMailableFactory
     */
    private $mailableFactory;

    /**
     * The mailer.
     *
     * @var UserMailer
     */
    private $mailer;

    /**
     * The route name.
     *
     * @var string
     */
    private $route;

    /**
     * The url generator.
     *
     * @var UserUrlGenerator
     */
    private $urlGenerator;

    /**
     * Constructor.
     *
     * @param UserMailer          $aMailer          The mailer
     * @param UserMailableFactory $aMailableFactory The mailable factory
     * @param UserUrlGenerator    $anUrlGenerator   The url generator
     * @param string              $aRoute           The route name
     * @param string|null         $aUserClass       The fully qualified user class name
     */
    public function __construct(
        UserMailer $aMailer,
        UserMailableFactory $aMailableFactory,
        UserUrlGenerator $anUrlGenerator,
        $aRoute,
        $aUserClass = null
    ) {
        $this->mailer = $aMailer;
        $this->mailableFactory = $aMailableFactory;
        $this->urlGenerator = $anUrlGenerator;
        $this->route = $aRoute;
        $this->userClass = $aUserClass;
    }

    /**
     * {@inheritdoc}
     *
     * @param UserInvited $aDomainEvent The domain event
     */
    public function handle($aDomainEvent)
    {
        $guest = $aDomainEvent->userGuest();
        $url = $this->urlGenerator->generate($this->route, [
            'invitation-token' => $guest->invitationToken()->token(),
        ]);
        $mail = $this->mailableFactory->build($guest->email(), [
            'user' => $guest, 'url' => $url,
        ]);

        $this->mailer->mail($mail);
    }

    /**
     * {@inheritdoc}
     *
     * @param UserInvited $aDomainEvent The domain event
     */
    public function isSubscribedTo($aDomainEvent)
    {
        if (null !== $this->userClass && $this->userClass !== get_class($aDomainEvent->userGuest())) {
            return false;
        }

        return $aDomainEvent instanceof UserInvited;
    }
}
