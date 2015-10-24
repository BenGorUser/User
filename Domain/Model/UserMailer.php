<?php

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
     * @param array $aSubject The subject of the email
     * @param array $from     Mail sending address
     * @param array $to       Mail receiving address
     * @param array $aBody    Mail body
     */
    public function mail($aSubject, $from, $to, $aBody);
}
