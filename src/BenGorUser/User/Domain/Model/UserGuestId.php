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
 * User guest id domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserGuestId
{
    /**
     * The id in a primitive type.
     *
     * @var string|int
     */
    private $id;

    /**
     * Constructor.
     *
     * @param string|int|null $anId The id in a primitive type
     */
    public function __construct($anId = null)
    {
        $this->id = null === $anId ? Uuid::uuid4()->toString() : $anId;
    }

    /**
     * Gets the id.
     *
     * @return string|int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Method that checks if the id given is equal to the current.
     *
     * @param UserGuestId $anId The id
     *
     * @return bool
     */
    public function equals(UserGuestId $anId)
    {
        return $this->id() === $anId->id();
    }

    /**
     * Magic method that represents the user guest id in string format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id();
    }
}
