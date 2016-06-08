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

namespace BenGorUser\User\Domain\Model;

use BenGorUser\User\Domain\Model\Exception\UserEmailInvalidException;

/**
 * User mailable domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserMailable
{
    /**
     * The receiver or receivers.
     *
     * @var array|UserEmail
     */
    private $to;

    /**
     * The from address.
     *
     * @var UserEmail
     */
    private $from;

    /**
     * The subject.
     *
     * @var string
     */
    private $subject;

    /**
     * The body in plain text.
     *
     * @var string
     */
    private $bodyText;

    /**
     * The body in HTML.
     *
     * @var string|null
     */
    private $bodyHtml;

    /**
     * Constructor.
     *
     * @param array|UserEmail $to        The receiver or receivers
     * @param UserEmail       $from      The from address
     * @param string          $aSubject  The subject
     * @param string          $aBodyText The body in plain text
     * @param string          $aBodyHtml The body in HTML, can be null
     */
    public function __construct($to, UserEmail $from, $aSubject, $aBodyText, $aBodyHtml = null)
    {
        if (is_array($to)) {
            $this->to = array_map(function ($receiver) {
                if (!$receiver instanceof UserEmail) {
                    throw new UserEmailInvalidException();
                }

                return $receiver;
            }, $to);
        } elseif ($to instanceof UserEmail) {
            $this->to = $to;
        } else {
            throw new UserEmailInvalidException();
        }

        $this->from = $from;
        $this->subject = $aSubject;
        $this->bodyText = $aBodyText;
        $this->bodyHtml = $aBodyHtml;
    }

    /**
     * Gets the receiver or receivers.
     *
     * @return array|UserEmail
     */
    public function to()
    {
        return $this->to;
    }

    /**
     * Gets the from address.
     *
     * @return UserEmail
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * Gets the subject.
     *
     * @return string
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * Gets the body in plain text.
     *
     * @return string
     */
    public function bodyText()
    {
        return $this->bodyText;
    }

    /**
     * Gets the body in HTML.
     *
     * @return string|null
     */
    public function bodyHtml()
    {
        return $this->bodyHtml;
    }
}
