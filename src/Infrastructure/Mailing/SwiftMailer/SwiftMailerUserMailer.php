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

use BenGor\User\Domain\Model\Exception\UserInvalidEmailException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserMailer;

/**
 * SwiftMailer user mailer class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class SwiftMailerUserMailer implements UserMailer
{
    /**
     * The swift mailer instance.
     *
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * Constructor.
     *
     * @param \Swift_Mailer $swiftMailer The swift mailer instance
     */
    public function __construct(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * {@inheritdoc}
     */
    public function mail($aSubject, UserEmail $from, $to, $aBody)
    {
        if (is_array($to)) {
            $receivers = array_map(function ($receiver) {
                if (!$receiver instanceof UserEmail) {
                    throw new UserInvalidEmailException();
                }

                return $receiver->email();
            }, $to);
            $to = $receivers;
        } elseif ($to instanceof UserEmail) {
            $to = $to->email();
        } else {
            throw new UserInvalidEmailException();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($aSubject)
            ->setFrom($from->email())
            ->setTo($to)
            ->setBody($aBody);
        $this->swiftMailer->send($message);
    }
}
