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

namespace BenGor\User\Domain\Model;

/**
 * User mailer domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserMailer
{
    /**
     * Mails an email with the given data.
     *
     * @param string    $aSubject The subject of the email
     * @param UserEmail $from     The mail sending address
     * @param array     $to       The mail receiving address
     * @param string    $aBody    The mail body
     */
    public function mail($aSubject, UserEmail $from, $to, $aBody);
}
