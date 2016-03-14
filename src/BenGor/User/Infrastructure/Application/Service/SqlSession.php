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

/**
 * Class SqlSession.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SqlSession implements TransactionalSession
{
    /**
     * The database abstraction layer.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * Constructor.
     *
     * @param \PDO $pdo The database abstraction layer
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function executeAtomically(callable $operation)
    {
        $this->pdo->beginTransaction();
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        try {
            $return = call_user_func($operation, $this);
            $this->pdo->commit();

            return $return ?: true;
        } catch (\Exception $exception) {
            $this->pdo->rollback();
            throw $exception;
        }
    }
}
