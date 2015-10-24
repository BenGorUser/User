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

namespace BenGor\User\Domain\Model;

use Ramsey\Uuid\Uuid;

/**
 * User confirmation domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserConfirmationToken
{
    /**
     * The id in a primitive type.
     *
     * @var string|int
     */
    private $token;

    /**
     * Constructor.
     *
     * @param string | null $token User confirmation token. New will be generated if empty
     */
    public function __construct($token = null)
    {
        $this->token = $token ?: Uuid::uuid4()->toString();
    }

    /**
     * Gets the id.
     *
     * @return string|int
     */
    public function token()
    {
        return $this->token;
    }

    /**
     * Method that checks if the id given is equal to the current.
     *
     * @param UserConfirmationToken $aToken The token
     *
     * @return bool
     */
    public function equals(UserConfirmationToken $aToken)
    {
        return $this->token() === $aToken->token();
    }

    /**
     * Magic method that represents the token in string format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->token();
    }
}
