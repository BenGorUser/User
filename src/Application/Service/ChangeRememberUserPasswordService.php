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

use Ddd\Application\Service\ApplicationService;

/**
 * Change remember user password service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class ChangeRememberUserPasswordService implements ApplicationService
{
    /**
     * The change user password service.
     *
     * @var ChangeUserPasswordService
     */
    private $changeUserPasswordService;

    /**
     * Constructor.
     *
     * @param ChangeUserPasswordService $aChangeUserPasswordService The change user password service
     */
    public function __construct(ChangeUserPasswordService $aChangeUserPasswordService)
    {
        $this->changeUserPasswordService = $aChangeUserPasswordService;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request = null)
    {
        $this->changeUserPasswordService->execute($request);
    }
}
