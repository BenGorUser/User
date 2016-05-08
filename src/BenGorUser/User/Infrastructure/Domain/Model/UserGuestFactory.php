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

namespace BenGorUser\User\Infrastructure\Domain\Model;

use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestFactory as BaseUserGuestFactory;
use BenGorUser\User\Domain\Model\UserGuestId;

/**
 * Implementation of user guest factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class UserGuestFactory implements BaseUserGuestFactory
{
    /**
     * The entity fully qualified namespace.
     *
     * @var string
     */
    private $class;

    /**
     * Constructor.
     *
     * @param string $aClass The entity fully qualified namespace
     */
    public function __construct($aClass = UserGuest::class)
    {
        $this->class = $aClass;
    }

    /**
     * {@inheritdoc}
     */
    public function register(UserGuestId $anId, UserEmail $anEmail)
    {
        return new $this->class($anId, $anEmail);
    }
}
