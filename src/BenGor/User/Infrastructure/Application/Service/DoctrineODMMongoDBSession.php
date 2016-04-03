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

namespace BenGor\User\Infrastructure\Application\Service;

use Ddd\Application\Service\TransactionalSession;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class Doctrine ODM MongoDB session class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineODMMongoDBSession implements TransactionalSession
{
    /**
     * The document manager.
     *
     * @var ObjectManager
     */
    private $documentManager;

    /**
     * Constructor.
     *
     * @param ObjectManager $documentManager The document manager
     */
    public function __construct(ObjectManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * {@inheritdoc}
     */
    public function executeAtomically(callable $operation)
    {
        $result = call_user_func($operation);
        $this->documentManager->flush();

        return $result;
    }
}
