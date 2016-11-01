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

namespace BenGorUser\User\Domain\Event;

use BenGorUser\User\Domain\Model\Event\UserEvent;
use BenGorUser\User\Domain\Model\Event\UserInvitationTokenRegenerated;
use BenGorUser\User\Domain\Model\Event\UserInvited;
use BenGorUser\User\Domain\Model\UserMailableFactory;
use BenGorUser\User\Domain\Model\UserMailer;
use BenGorUser\User\Domain\Model\UserUrlGenerator;

/**
 * User invited mailer subscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserInvitedMailerSubscriber implements UserEventSubscriber
{
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
     */
    public function __construct(
        UserMailer $aMailer,
        UserMailableFactory $aMailableFactory,
        UserUrlGenerator $anUrlGenerator,
        $aRoute
    ) {
        $this->mailer = $aMailer;
        $this->mailableFactory = $aMailableFactory;
        $this->urlGenerator = $anUrlGenerator;
        $this->route = $aRoute;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(UserEvent $anEvent)
    {
        $url = $this->urlGenerator->generate($this->route, [
            'invitation-token' => $anEvent->invitationToken()->token(),
        ]);
        $mail = $this->mailableFactory->build($anEvent->email(), [
            'email' => $anEvent->email(), 'url' => $url,
        ]);

        $this->mailer->mail($mail);
    }

    /**
     * {@inheritdoc}
     */
    public function isSubscribedTo(UserEvent $anEvent)
    {
        return $anEvent instanceof UserInvited || $anEvent instanceof UserInvitationTokenRegenerated;
    }
}
