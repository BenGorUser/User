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
 * User of id query.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfIdQuery
{
    /**
     * The user id.
     *
     * @var string
     */
    private $id;

    /**
     * Constructor.
     *
     * @param string $anId The user id
     */
    public function __construct($anId)
    {
        if (null === $anId) {
            throw new \InvalidArgumentException('Id cannot be null');
        }
        $this->id = $anId;
    }

    /**
     * Gets the id.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
    }
}
