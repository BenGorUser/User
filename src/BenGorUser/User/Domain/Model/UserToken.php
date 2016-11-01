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

namespace BenGorUser\User\Domain\Model;

use Ramsey\Uuid\Uuid;

/**
 * User token domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserToken
{
    /**
     * The id in a primitive type.
     *
     * @var string|int
     */
    private $token;

    /**
     * The created on.
     *
     * @var \DateTimeImmutable
     */
    private $createdOn;

    /**
     * Constructor.
     *
     * @param string|null $token User token. New will be generated if empty
     */
    public function __construct($token = null)
    {
        $this->token = $token ?: Uuid::uuid4()->toString();
        $this->createdOn = new \DateTimeImmutable();
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
     * Gets the created on.
     *
     * @return \DateTimeImmutable
     */
    public function createdOn()
    {
        return $this->createdOn;
    }

    /**
     * Method that checks if the id given is equal to the current.
     *
     * @param UserToken $aToken The token
     *
     * @return bool
     */
    public function equals(UserToken $aToken)
    {
        return $this->token === $aToken->token();
    }

    /**
     * Checks if the token is expired comparing
     * with the given lifetime.
     *
     * @param int $lifetime The lifetime of the token
     *
     * @return bool
     */
    public function isExpired($lifetime)
    {
        $interval = $this->createdOn->diff(new \DateTimeImmutable());

        return $interval->s >= (int) $lifetime;
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
