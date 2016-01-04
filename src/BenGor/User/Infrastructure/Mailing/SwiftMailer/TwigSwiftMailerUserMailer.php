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

namespace BenGor\User\Infrastructure\Mailing\SwiftMailer;

use BenGor\User\Domain\Model\Exception\UserEmailInvalidException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserMailer;

/**
 * Twig SwiftMailer user mailer class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class TwigSwiftMailerUserMailer implements UserMailer
{
    /**
     * The swift mailer instance.
     *
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * The Twig engine.
     *
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer     $swiftMailer The swift mailer instance
     * @param \Twig_Environment $twig        The twig environment
     */
    public function __construct(\Swift_Mailer $swiftMailer, \Twig_Environment $twig)
    {
        $this->swiftMailer = $swiftMailer;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function mail($aSubject, UserEmail $from, $to, $aContent, array $parameters = [])
    {
        if (is_array($to)) {
            $receivers = array_map(function ($receiver) {
                if (!$receiver instanceof UserEmail) {
                    throw new UserEmailInvalidException();
                }

                return $receiver->email();
            }, $to);
            $to = $receivers;
        } elseif ($to instanceof UserEmail) {
            $to = $to->email();
        } else {
            throw new UserEmailInvalidException();
        }

        $template = $this->twig->loadTemplate($aContent);
        $bodyText = $template->renderBlock('body_text', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);

        $message = \Swift_Message::newInstance()
            ->setSubject($aSubject)
            ->setFrom($from->email())
            ->setTo($to)
            ->setBody($bodyText, 'text/plain')
            ->addPart($bodyHtml, 'text/html');
        $this->swiftMailer->send($message);
    }
}
