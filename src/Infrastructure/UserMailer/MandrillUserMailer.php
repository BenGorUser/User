<?php

/*
 * This file is part of the User library.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\User\Infrastructure\UserMailer;

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
    public function mail($aSubject, $from, $to, $aBody)
    {
        // TODO: Implement mail() method.
    }
}
