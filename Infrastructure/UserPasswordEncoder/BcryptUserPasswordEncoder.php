<?php

namespace BenGor\User\Infrastructure\UserPasswordEncoder;

use BenGor\User\Domain\Model\UserPasswordEncoder;

/**
 * BCrypt user password encoder class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class BcryptUserPasswordEncoder implements UserPasswordEncoder
{
    /**
     * {@inheritdoc}
     */
    public function encode($aPlainPassword, $aSalt)
    {
        // TODO: Implement encode() method.
    }
}
