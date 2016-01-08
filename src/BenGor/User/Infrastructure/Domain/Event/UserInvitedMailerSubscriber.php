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

namespace BenGor\User\Infrastructure\Domain\Event;

use BenGor\User\Domain\Model\Event\UserInvited;
use BenGor\User\Domain\Model\UserMailableFactory;
use BenGor\User\Domain\Model\UserMailer;
use BenGor\User\Domain\Event\UserInvitedMailerSubscriber as BaseUserInvitedMailerSubscriber;
use Symfony\Component\Routing\Router;

/**
 * User invited mailer subscriber class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserInvitedMailerSubscriber extends BaseUserInvitedMailerSubscriber
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
     * The prefix of url.
     *
     * @var string
     */
    private $pattern;

    /**
     * The Symfony router component.
     *
     * @var Router
     */
    private $router;

    /**
     * Constructor.
     *
     * @param UserMailer          $aMailer          The mailer
     * @param UserMailableFactory $aMailableFactory The mailable factory
     * @param Router              $aRouter          The Symfony router
     * @param string              $aPattern         The pattern
     */
    public function __construct(UserMailer $aMailer, UserMailableFactory $aMailableFactory, Router $aRouter, $aPattern)
    {
        parent::__construct($aMailer, $aMailableFactory);
        $this->router = $aRouter;
        $this->pattern = '';
        if ('' !== $aPattern) {
            $this->pattern = $aPattern . '_';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handle($aDomainEvent)
    {
        $guest = $aDomainEvent->userGuest();
        $mail = $this->mailableFactory->build('AQUI HACEMOS TODO LO REFERENTE A LA GENERACIÓN DE LA RUTA');

        $this->mailer->mail($mail);
    }
}
