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

namespace BenGor\User\Infrastructure\Mailing\Mandrill;

use BenGor\User\Domain\Model\Exception\UserEmailInvalidException;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserMailer;

/**
 * Mandrill user mailer class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class MandrillUserMailer implements UserMailer
{
    /**
     * The mandrill instance.
     *
     * @var \Mandrill
     */
    private $mandrill;

    /**
     * Constructor.
     *
     * @param \Mandrill $mandrill The mandrill instance
     */
    public function __construct(\Mandrill $mandrill)
    {
        $this->mandrill = $mandrill;
    }

    /**
     * {@inheritdoc}
     */
    public function mail($aSubject, UserEmail $from, $to, $aBody)
    {
        if (is_array($to)) {
            $receivers = array_map(function ($receiver) {
                if (!$receiver instanceof UserEmail) {
                    throw new UserEmailInvalidException();
                }

                return [
                    'email' => $receiver->email(),
                    'name'  => $receiver->email(),
                    'type'  => 'to',
                ];
            }, $to);
            $to = $receivers;
        } elseif ($to instanceof UserEmail) {
            $to = $to->email();
        } else {
            throw new UserEmailInvalidException();
        }

        $message = [
            'subject'             => $aSubject,
            'from_email'          => $from->email(),
            'from_name'           => $from->email(),
            'to'                  => $to,
            'headers'             => ['Reply-To' => $from->email()],
            'important'           => true,
            'track_opens'         => true,
            'track_clicks'        => null,
            'auto_text'           => $aBody,
            'auto_html'           => null,
            'inline_css'          => null,
            'url_strip_qs'        => null,
            'preserve_recipients' => false,
            'view_content_link'   => false,
            'tracking_domain'     => null,
            'signing_domain'      => null,
            'return_path_domain'  => null,
            'merge'               => true,
            'tags'                => [],
            'global_merge_vars'   => [$to],
        ];

        $this->mandrill->messages->send($message);
    }
}
