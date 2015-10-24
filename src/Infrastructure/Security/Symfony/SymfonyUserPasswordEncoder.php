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

namespace BenGor\User\Infrastructure\Security\Symfony;

use BenGor\User\Domain\Model\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Symfony user password encoder class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class SymfonyUserPasswordEncoder implements UserPasswordEncoder
{
    /**
     * The password encoder.
     *
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * Constructor.
     *
     * @param PasswordEncoderInterface $aPasswordEncoder The password encoder
     */
    public function __construct(PasswordEncoderInterface $aPasswordEncoder)
    {
        $this->passwordEncoder = $aPasswordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($aPlainPassword, $aSalt)
    {
        $encodedPassword = $this->passwordEncoder->encodePassword($aPlainPassword, $aSalt);

        if (false === $this->passwordEncoder->isPasswordValid($encodedPassword, $aPlainPassword, $aSalt)) {
            throw new \Exception('The password is invalid');
        }

        return $encodedPassword;
    }
}
