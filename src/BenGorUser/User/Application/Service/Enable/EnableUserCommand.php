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

namespace BenGorUser\User\Application\Service\Enable;

/**
 * Enable user command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class EnableUserCommand
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
