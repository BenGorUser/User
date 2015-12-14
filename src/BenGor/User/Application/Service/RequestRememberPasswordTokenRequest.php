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

namespace BenGor\User\Application\Service;

/**
 * Request remember password token request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class RequestRememberPasswordTokenRequest
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
     * Gets the user email.
     *
     * @return string
     */
    public function email()
    {
        return $this->email;
    }
}
