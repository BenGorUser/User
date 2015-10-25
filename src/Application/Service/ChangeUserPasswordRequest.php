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

namespace BenGor\User\Application\Service;

/**
 * Change user password request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class ChangeUserPasswordRequest
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
     *
     * @internal param string $aPlainPassword The user plain password
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
