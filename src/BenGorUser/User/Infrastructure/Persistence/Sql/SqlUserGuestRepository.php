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

namespace BenGorUser\User\Infrastructure\Persistence\Sql;

use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserGuest;
use BenGorUser\User\Domain\Model\UserGuestId;
use BenGorUser\User\Domain\Model\UserGuestRepository;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Domain\Model\UserEventBus;

/**
 * Sql user guest repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class SqlUserGuestRepository implements UserGuestRepository
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * The pdo instance.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * The user event bus.
     *
     * @var UserEventBus
     */
    private $eventBus;

    /**
     * Constructor.
     *
     * @param \PDO         $aPdo       The pdo instance
     * @param UserEventBus $anEventBus The user event bus
     */
    public function __construct(\PDO $aPdo, UserEventBus $anEventBus)
    {
        $this->pdo = $aPdo;
        $this->eventBus = $anEventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfId(UserGuestId $anId)
    {
        $statement = $this->execute('SELECT * FROM user_guest WHERE id = :id', ['id' => $anId->id()]);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildUserGuest($row);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfEmail(UserEmail $anEmail)
    {
        $statement = $this->execute('SELECT * FROM user_guest WHERE email = :email', ['email' => $anEmail->email()]);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildUserGuest($row);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userGuestOfInvitationToken(UserToken $anInvitationToken)
    {
        $statement = $this->execute('SELECT * FROM user_guest WHERE invitation_token = :invitationToken', [
            'invitationToken' => $anInvitationToken->token(),
        ]);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildUserGuest($row);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function persist(UserGuest $aUserGuest)
    {
        if ($this->exist($aUserGuest)) {
            return;
        }
        $this->insert($aUserGuest);

        foreach ($aUserGuest->events() as $event) {
            $this->eventBus->handle($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(UserGuest $aUserGuest)
    {
        $this->execute('DELETE FROM user_guest WHERE id = :id', ['id' => $aUserGuest->id()->id()]);

        foreach ($aUserGuest->events() as $event) {
            $this->eventBus->handle($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return $this->pdo->query('SELECT COUNT(*) FROM user_guest')->fetchColumn();
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserGuestId();
    }

    /**
     * Loads the user guest schema into database create the
     * table with user attribute properties as columns.
     */
    public function initSchema()
    {
        $this->pdo->exec(<<<SQL
DROP TABLE IF EXISTS user;
CREATE TABLE user_guest (
    id CHAR(36) PRIMARY KEY,
    created_on DATETIME NOT NULL,
    email VARCHAR(36) NOT NULL,
    invitation_token VARCHAR(36)
)
SQL
        );
    }

    /**
     * Checks if the user guest given exists in the database.
     *
     * @param UserGuest $aUserGuest The user
     *
     * @return bool
     */
    private function exist(UserGuest $aUserGuest)
    {
        $count = $this->execute(
            'SELECT COUNT(*) FROM user_guest WHERE id = :id', [':id' => $aUserGuest->id()->id()]
        )->fetchColumn();

        return $count === 1;
    }

    /**
     * Prepares the insert SQL with the user guest given.
     *
     * @param UserGuest $aUserGuest The user
     */
    private function insert(UserGuest $aUserGuest)
    {
        $sql = 'INSERT INTO user_guest (id, created_on, email, invitation_token) VALUES (:id, :createdOn, :email, :token)';
        $this->execute($sql, [
            'id'        => $aUserGuest->id()->id(),
            'createdOn' => $aUserGuest->createdOn()->format(self::DATE_FORMAT),
            'email'     => $aUserGuest->email()->email(),
            'token'     => $aUserGuest->invitationToken()->token(),
        ]);
    }

    /**
     * Wrapper that encapsulates the same
     * logic about execute the query in PDO.
     *
     * @param string $aSql       The SQL
     * @param array  $parameters Array which contains the parameters of SQL
     *
     * @return \PDOStatement
     */
    private function execute($aSql, array $parameters)
    {
        $statement = $this->pdo->prepare($aSql);
        $statement->execute($parameters);

        return $statement;
    }

    /**
     * Builds the user with the given sql row attributes.
     *
     * @param array $row Array which contains attributes of user
     *
     * @return UserGuest
     */
    private function buildUserGuest($row)
    {
        return new UserGuest(
            new UserGuestId($row['id']),
            new UserEmail($row['email']),
            new \DateTimeImmutable($row['created_on']),
            new UserToken($row['invitation_token'])
        );
    }
}
