<?php

namespace BenGor\User\Infrastructure\Persistence\Doctrine;

use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserConfirmationToken;
use BenGor\User\Domain\Model\UserEmail;
use BenGor\User\Domain\Model\UserId;
use BenGor\User\Domain\Model\UserRepository;

/**
 * In memory user repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class InMemoryUserRepository implements UserRepository
{
    /**
     * Array which contains the users.
     *
     * @var array
     */
    private $users;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->users = [];
    }

    /**
     * {@inheritdoc}
     */
    public function userOfId(UserId $anId)
    {
        if (isset($this->users[$anId->id()])) {
            return $this->users[$anId->id()];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function userOfEmail(UserEmail $anEmail)
    {
        if (isset($this->users[$anEmail->email()])) {
            return $this->users[$anEmail->email()];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function userOfConfirmationToken(UserConfirmationToken $aConfirmationToken)
    {
        throw new \Exception('Not implemented yet');
    }

    /**
     * {@inheritdoc}
     */
    public function query($specification)
    {
        return array_values(
            array_filter(
                $this->users,
                function (User $aUser) use ($specification) {
                    return $specification->specifies($aUser);
                }
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $aUser)
    {
        $this->users[$aUser->id()->id()] = $aUser;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $aUser)
    {
        unset($this->users[$aUser->id()->id()]);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return count($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}
