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
 * User of invitation token query.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfInvitationTokenQuery
{
    /**
     * The user invitationToken.
     *
     * @var string
     */
    private $invitationToken;

    /**
     * Constructor.
     *
     * @param string $anInvitationToken The user invitationToken
     */
    public function __construct($anInvitationToken)
    {
        if (null === $anInvitationToken) {
            throw new \InvalidArgumentException('Invitation token cannot be null');
        }
        $this->invitationToken = $anInvitationToken;
    }

    /**
     * Gets the invitation token.
     *
     * @return string
     */
    public function invitationToken()
    {
        return $this->invitationToken;
    }
}
