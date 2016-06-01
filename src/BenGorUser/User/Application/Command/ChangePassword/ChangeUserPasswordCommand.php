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

namespace BenGorUser\User\Application\Command\ChangePassword;

/**
 * Change user password command class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ChangeUserPasswordCommand
{
    /**
     * The user id.
     *
     * @var string
     */
    private $id;

    /**
     * The new plain password.
     *
     * @var string
     */
    private $newPlainPassword;

    /**
     * The old plain password.
     *
     * @var string
     */
    private $oldPlainPassword;

    /**
     * Constructor.
     *
     * @param string $anId               The user id
     * @param string $aNewPlainPassword  The new plain password
     * @param string $anOldPlainPassword The old plain password
     */
    public function __construct($anId, $aNewPlainPassword, $anOldPlainPassword)
    {
        $this->id = $anId;
        $this->newPlainPassword = $aNewPlainPassword;
        $this->oldPlainPassword = $anOldPlainPassword;
    }

    /**
     * Gets the user id.
     *
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Gets the new plain password.
     *
     * @return string
     */
    public function newPlainPassword()
    {
        return $this->newPlainPassword;
    }

    /**
     * Gets the old plain password.
     *
     * @return string
     */
    public function oldPlainPassword()
    {
        return $this->oldPlainPassword;
    }
}
