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

namespace BenGor\User\Infrastructure\Persistence\Sql;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserPassword;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserRole;
use BenGor\User\Domain\Model\UserToken;

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
     * Constructor.
     *
     * @param \PDO $aPdo The pdo instance
     */
    public function __construct(\PDO $aPdo)
    {
        $this->pdo = $aPdo;
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
    public function query($specification)
    {
        // TODO: Implement query() method.
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $aUser)
    {
        ($this->exist($aUser)) ? $this->update($aUser) : $this->insert($aUser);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $aUser)
    {
        $this->execute('DELETE FROM user WHERE id = :id', ['id' => $aUser->id()->id()]);
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

        return $count === 1;
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
            :rememberPasswordToken,
            :roles,
            :updatedOn
        )';
        $this->execute($sql, [
            'id'                    => $aUser->id()->id(),
            'token'                 => $aUser->confirmationToken()->token(),
            'createdOn'             => $aUser->createdOn()->format(self::DATE_FORMAT),
            'email'                 => $aUser->email()->email(),
            'lastLogin'             => $aUser->lastLogin()->format(self::DATE_FORMAT),
            'password'              => $aUser->password()->encodedPassword(),
            'rememberPasswordToken' => $aUser->rememberPasswordToken()->token(),
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
            remember_password_token = :rememberPasswordToken
            roles = :roles,
            updated_on = :updatedOn,
            WHERE id = :id';
        $this->execute($sql, [
            'id'                    => $aUser->id()->id(),
            'confirmationToken'     => $aUser->confirmationToken()->token(),
            'lastLogin'             => $aUser->lastLogin(),
            'password'              => $aUser->password()->encodedPassword(),
            'rememberPasswordToken' => $aUser->rememberPasswordToken()->token(),
            'roles'                 => $this->rolesToString($aUser->roles()),
            'updatedOn'             => $aUser->updatedOn(),
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
        $lastLogin = null === $row['last_login']
            ? null
            : new \DateTime($row['last_login']);
        $confirmationToken = null === $row['confirmation_token']
            ? null
            : new UserToken($row['confirmation_token']);
        $rememberPasswordToken = null === $row['remember_password_token']
            ? null
            : new UserToken($row['remember_password_token']);

        return new User(
            new UserId($row['id']),
            new UserEmail($row['email']),
            UserPassword::fromEncoded($row['password'], $row['salt']),
            $this->rolesToArray($row['user_roles']),
            new \DateTime($row['created_on']),
            new \DateTime($row['updated_on']),
            $lastLogin,
            $confirmationToken,
            $rememberPasswordToken
        );
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
            array_map(function ($userRole) {
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
}
