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

use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserPassword;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Domain\Model\UserRole;
use BenGorUser\User\Domain\Model\UserToken;
use BenGorUser\User\Infrastructure\Domain\Model\UserEventBus;

/**
 * Sql user repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class SqlUserRepository implements UserRepository
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
    public function userOfId(UserId $anId)
    {
        $statement = $this->execute('SELECT * FROM user WHERE id = :id', ['id' => $anId->id()]);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildUser($row);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userOfEmail(UserEmail $anEmail)
    {
        $statement = $this->execute('SELECT * FROM user WHERE email = :email', ['email' => $anEmail->email()]);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildUser($row);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userOfConfirmationToken(UserToken $aConfirmationToken)
    {
        $statement = $this->execute('SELECT * FROM user WHERE confirmation_token = :confirmationToken', [
            'confirmationToken' => $aConfirmationToken->token(),
        ]);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildUser($row);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function userOfRememberPasswordToken(UserToken $aRememberPasswordToken)
    {
        $statement = $this->execute('SELECT * FROM user WHERE remember_password_token = :rememberPasswordToken', [
            'rememberPasswordToken' => $aRememberPasswordToken->token(),
        ]);
        if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            return $this->buildUser($row);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $aUser)
    {
        ($this->exist($aUser)) ? $this->update($aUser) : $this->insert($aUser);

        foreach ($aUser->events() as $event) {
            $this->eventBus->handle($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $aUser)
    {
        $this->execute('DELETE FROM user WHERE id = :id', ['id' => $aUser->id()->id()]);

        foreach ($aUser->events() as $event) {
            $this->eventBus->handle($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return $this->pdo->query('SELECT COUNT(*) FROM user')->fetchColumn();
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserId();
    }

    /**
     * Loads the user schema into database create the table
     * with user attribute properties as columns.
     */
    public function initSchema()
    {
        $this->pdo->exec(<<<SQL
DROP TABLE IF EXISTS user;
CREATE TABLE user (
    id CHAR(36) PRIMARY KEY,
    confirmation_token VARCHAR(36),
    created_on DATETIME NOT NULL,
    email VARCHAR(36) NOT NULL,
    last_login DATETIME,
    password VARCHAR(30) NOT NULL,
    remember_password_token VARCHAR(36),
    roles LONGTEXT NOT NULL COMMENT '(DC2Type:user_roles)',
    updated_on DATETIME NOT NULL
)
SQL
        );
    }

    /**
     * Checks if the user given exists in the database.
     *
     * @param User $aUser The user
     *
     * @return bool
     */
    private function exist(User $aUser)
    {
        $count = $this->execute(
            'SELECT COUNT(*) FROM user WHERE id = :id', [':id' => $aUser->id()->id()]
        )->fetchColumn();

        return (int) $count === 1;
    }

    /**
     * Prepares the insert SQL with the user given.
     *
     * @param User $aUser The user
     */
    private function insert(User $aUser)
    {
        $sql = 'INSERT INTO user (
            id,
            confirmation_token,
            created_on,
            email,
            last_login,
            password,
            salt,
            remember_password_token,
            roles,
            updated_on
        ) VALUES (
            :id,
            :token,
            :createdOn,
            :email,
            :lastLogin,
            :password,
            :salt,
            :rememberPasswordToken,
            :roles,
            :updatedOn
        )';
        $this->execute($sql, [
            'id'                    => $aUser->id()->id(),
            'token'                 => $aUser->confirmationToken(),
            'createdOn'             => $aUser->createdOn()->format(self::DATE_FORMAT),
            'email'                 => $aUser->email()->email(),
            'lastLogin'             => $aUser->lastLogin() ? $aUser->lastLogin()->format(self::DATE_FORMAT) : null,
            'password'              => $aUser->password()->encodedPassword(),
            'salt'                  => $aUser->password()->salt(),
            'rememberPasswordToken' => $aUser->rememberPasswordToken() ? $aUser->rememberPasswordToken()->token() : null,
            'roles'                 => $this->rolesToString($aUser->roles()),
            'updatedOn'             => $aUser->updatedOn()->format(self::DATE_FORMAT),
        ]);
    }

    /**
     * Prepares the update SQL with the user given.
     *
     * @param User $aUser The user
     */
    private function update(User $aUser)
    {
        $sql = 'UPDATE user SET
            confirmation_token = :confirmationToken,
            last_login = :lastLogin,
            password = :password,
            remember_password_token = :rememberPasswordToken,
            roles = :roles,
            updated_on = :updatedOn
            WHERE id = :id';
        $this->execute($sql, [
            'id'                    => $aUser->id()->id(),
            'confirmationToken'     => $aUser->confirmationToken() ? $aUser->confirmationToken()->token() : null,
            'lastLogin'             => $aUser->lastLogin() ? $aUser->lastLogin()->format(self::DATE_FORMAT) : null,
            'password'              => $aUser->password()->encodedPassword(),
            'rememberPasswordToken' => $aUser->rememberPasswordToken() ? $aUser->rememberPasswordToken()->token() : null,
            'roles'                 => $this->rolesToString($aUser->roles()),
            'updatedOn'             => $aUser->updatedOn()->format(self::DATE_FORMAT),
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
     * @return User
     */
    private function buildUser($row)
    {
        $createdOn = new \DateTimeImmutable($row['created_on']);
        $updatedOn = new \DateTimeImmutable($row['updated_on']);
        $lastLogin = null === $row['last_login']
            ? null
            : new \DateTimeImmutable($row['last_login']);
        $confirmationToken = null === $row['confirmation_token']
            ? null
            : new UserToken($row['confirmation_token']);
        $rememberPasswordToken = null === $row['remember_password_token']
            ? null
            : new UserToken($row['remember_password_token']);

        $user = new User(
            new UserId($row['id']),
            new UserEmail($row['email']),
            UserPassword::fromEncoded($row['password'], $row['salt']),
            $this->rolesToArray($row['roles'])
        );

        $user = $this->set($user, 'createdOn', $createdOn);
        $user = $this->set($user, 'updatedOn', $updatedOn);
        $user = $this->set($user, 'lastLogin', $lastLogin);
        $user = $this->set($user, 'confirmationToken', $confirmationToken);
        $user = $this->set($user, 'rememberPasswordToken', $rememberPasswordToken);

        return $user;
    }

    /**
     * Transforms given user roles into encoded plain json array.
     *
     * @param array $userRoles Array which contains the user roles
     *
     * @return string
     */
    private function rolesToString(array $userRoles)
    {
        return json_encode(
            array_map(function (UserRole $userRole) {
                return $userRole->role();
            }, $userRoles)
        );
    }

    /**
     * Transforms given user roles encoded array into user roles collection.
     *
     * @param array $userRoles Encoded json array
     *
     * @return UserRole[]
     */
    private function rolesToArray($userRoles)
    {
        return array_map(function ($userRole) {
            return new UserRole($userRole);
        }, json_decode($userRoles));
    }

    /**
     * Populates by Reflection the domain object with the given SQL plain values.
     *
     * @param User   $user          The user domain object
     * @param string $propertyName  The property name
     * @param mixed  $propertyValue The property value
     *
     * @return User
     */
    private function set(User $user, $propertyName, $propertyValue)
    {
        $reflectionUser = new \ReflectionClass($user);

        $reflectionCreatedOn = $reflectionUser->getProperty($propertyName);
        $reflectionCreatedOn->setAccessible(true);
        $reflectionCreatedOn->setValue($user, $propertyValue);

        return $user;
    }
}
