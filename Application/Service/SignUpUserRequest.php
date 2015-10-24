<?php

namespace BenGor\User\Application\Service;

/**
 * Sign up user request class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class SignUpUserRequest
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $email    User email
     * @param string $password User password
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * User email
     *
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * User password
     *
     * @return string
     */
    public function password()
    {
        return $this->password;
    }
}
