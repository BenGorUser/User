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

namespace spec\BenGor\User\Application\Service;

use BenGor\User\Application\Service\ActivateUserAccountRequest;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec file of activate user account service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class ActivateUserAccountServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BenGor\User\Application\Service\ActivateUserAccountService');

    }

    function it_activates_user(UserRepository $repository, User $user)
    {
        $request = new ActivateUserAccountRequest('dsfjadjfkdasjkfdajskf');

        $user->enableAccount()->shouldBeCalled();
        $repository->userOfConfirmationToken(Argument::type('BenGor\User\Domain\Model\UserConfirmationToken'))
            ->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_doesnt_activate_user_with_wrong_token(UserRepository $repository, User $user)
    {
        $request = new ActivateUserAccountRequest('dsfjadjfkdasjkfdajskf');

        $user->enableAccount()->shouldNotBeCalled();
        $repository->userOfConfirmationToken(Argument::type('BenGor\User\Domain\Model\UserConfirmationToken'))
            ->shouldBeCalled()->willReturn(null);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow('BenGor\User\Domain\Model\Exception\UserConfirmationTokenNotFoundException')
            ->duringExecute($request);
    }
}
