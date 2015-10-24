<?php

namespace BenGor\User\Application\Service;

/**
 * Sign up user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ActivateUserAccountRequest
{
    /**
     * @var string
     */
    private $confirmationToken;

    public function __construct($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function confirmationToken()
    {
        return $this->confirmationToken;
    }
}
