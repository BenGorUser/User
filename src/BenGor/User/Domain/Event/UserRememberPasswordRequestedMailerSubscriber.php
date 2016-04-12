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

use BenGor\User\Domain\Model\Event\UserRememberPasswordRequested;
use BenGor\User\Domain\Model\UserMailableFactory;
use BenGor\User\Domain\Model\UserMailer;
use BenGor\User\Domain\Model\UserUrlGenerator;
use Ddd\Domain\DomainEventSubscriber;

/**
 * User remember password requested mailer subscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserRememberPasswordRequestedMailerSubscriber implements DomainEventSubscriber
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
     * @param UserRememberPasswordRequested $aDomainEvent The domain event
     */
    public function handle($aDomainEvent)
    {
        $user = $aDomainEvent->user();
        $url = $this->urlGenerator->generate($this->route, [
            'remember-password-token' => $user->rememberPasswordToken()->token(),
        ]);
        $mail = $this->mailableFactory->build($user->email(), [
            'user' => $user, 'url' => $url,
        ]);

        $this->mailer->mail($mail);
    }

    /**
     * {@inheritdoc}
     *
     * @param UserRememberPasswordRequested $aDomainEvent The domain event
     */
    public function isSubscribedTo($aDomainEvent)
    {
        if (null !== $this->userClass && $this->userClass !== get_class($aDomainEvent->user())) {
            return false;
        }

        return $aDomainEvent instanceof UserRememberPasswordRequested;
    }
}
