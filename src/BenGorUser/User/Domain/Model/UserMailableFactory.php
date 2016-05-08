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

namespace BenGorUser\User\Domain\Model;

/**
 * User mailable factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
interface UserMailableFactory
{
    /**
     * Builds a user mailable domain object with the given parameters.
     *
     * @param array|UserEmail $to         Array which contains UserEmails or a simple UserEmail domain object
     * @param array           $parameters Array which contains parameters
     *
     * @return UserMailable
     */
    public function build($to, array $parameters = []);
}
