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

namespace BenGor\User\Infrastructure\Domain\Model;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserFactory as BaseUserFactory;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;

/**
 * Implementation of user factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class UserFactory implements BaseUserFactory
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
    public function __construct($aClass = User::class)
    {
        $this->class = $aClass;
    }

    /**
     * {@inheritdoc}
     */
    public function register(UserId $anId, UserEmail $anEmail, UserPassword $aPassword, array $roles)
    {
        return new $this->class($anId, $anEmail, $aPassword, $roles);
    }
}
