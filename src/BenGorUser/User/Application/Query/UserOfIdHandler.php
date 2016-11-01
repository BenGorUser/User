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

namespace BenGorUser\User\Application\Query;

use BenGorUser\User\Application\DataTransformer\UserDataTransformer;
use BenGorUser\User\Domain\Model\Exception\UserDoesNotExistException;
use BenGorUser\User\Domain\Model\UserId;
use BenGorUser\User\Domain\Model\UserRepository;

/**
 * User of id query handler.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserOfIdHandler
{
    /**
     * The user data transformer.
     *
     * @var UserDataTransformer
     */
    private $dataTransformer;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param UserRepository      $aRepository      The user repository
     * @param UserDataTransformer $aDataTransformer The user data transformer
     */
    public function __construct(UserRepository $aRepository, UserDataTransformer $aDataTransformer)
    {
        $this->repository = $aRepository;
        $this->dataTransformer = $aDataTransformer;
    }

    /**
     * Handles the given query.
     *
     * @param UserOfIdQuery $aQuery The query
     *
     * @throws UserDoesNotExistException when the user does not exist
     *
     * @return mixed
     */
    public function __invoke(UserOfIdQuery $aQuery)
    {
        $user = $this->repository->userOfId(new UserId($aQuery->id()));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $this->dataTransformer->write($user);

        return $this->dataTransformer->read();
    }
}
