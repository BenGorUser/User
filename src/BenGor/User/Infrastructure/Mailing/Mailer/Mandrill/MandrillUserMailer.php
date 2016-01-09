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

namespace BenGor\User\Infrastructure\Mailing\Mailer\Mandrill;

use BenGor\User\Domain\Model\UserMailable;
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
    public function mail(UserMailable $mail)
    {
        if (is_array($mail->to())) {
            $to = array_map(function ($receiver) {
                return $receiver->email();
            }, $mail->to());
        } else {
            $to = $mail->to();
        }

        $message = [
            'subject'             => $mail->subject(),
            'from_email'          => $mail->from()->email(),
            'from_name'           => $mail->from()->email(),
            'to'                  => $to,
            'headers'             => ['Reply-To' => $mail->from()->email()],
            'important'           => true,
            'track_opens'         => true,
            'track_clicks'        => null,
            'auto_text'           => $mail->bodyText(),
            'auto_html'           => $mail->bodyHtml(),
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
