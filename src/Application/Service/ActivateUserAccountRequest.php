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

namespace BenGor\User\Application\Service;

/**
 * Activate user account request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class ActivateUserAccountRequest
{
    /**
     * The confirmation token.
     *
     * @var string
     */
    private $confirmationToken;

    /**
     * Constructor.
     *
     * @param string $aConfirmationToken The confirmation token
     */
    public function __construct($aConfirmationToken)
    {
        $this->confirmationToken = $aConfirmationToken;
    }

    /**
     * Gets the confirmation token.
     *
     * @return string
     */
    public function confirmationToken()
    {
        return $this->confirmationToken;
    }
}
