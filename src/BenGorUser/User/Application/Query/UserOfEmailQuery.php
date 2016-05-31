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

namespace BenGorUser\User\Application\Query;

/**
 * User of email query.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfEmailQuery
{
    /**
     * The user email.
     *
     * @var string
     */
    private $email;

    /**
     * Constructor.
     *
     * @param string $anEmail The user email
     */
    public function __construct($anEmail)
    {
        $this->email = $anEmail;
    }

    /**
     * Gets the email.
     *
     * @return string
     */
    public function email()
    {
        return $this->email;
    }
}
