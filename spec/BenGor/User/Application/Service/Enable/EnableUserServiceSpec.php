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

namespace spec\BenGor\User\Application\Service\Enable;

use BenGor\User\Application\Service\Enable\EnableUserRequest;
use BenGor\User\Application\Service\Enable\EnableUserService;
use BenGor\User\Domain\Model\Exception\UserTokenNotFoundException;
use BenGor\User\Domain\Model\User;
use BenGor\User\Domain\Model\UserRepository;
use BenGor\User\Domain\Model\UserToken;
use Ddd\Application\Service\ApplicationService;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of enable user service class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class EnableUserServiceSpec extends ObjectBehavior
{
    function let(UserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EnableUserService::class);
    }

    function it_implements_application_service()
    {
        $this->shouldImplement(ApplicationService::class);
    }

    function it_activates_user(UserRepository $repository, User $user, EnableUserRequest $request)
    {
        $request->confirmationToken()->shouldBeCalled()->willReturn('confirmation-token');
        $user->enableAccount()->shouldBeCalled();
        $repository->userOfConfirmationToken(new UserToken('confirmation-token'))->shouldBeCalled()->willReturn($user);
        $repository->persist($user)->shouldBeCalled();

        $this->execute($request);
    }

    function it_doesnt_activate_user_with_wrong_token(
        UserRepository $repository,
        User $user,
        EnableUserRequest $request
    ) {
        $request->confirmationToken()->shouldBeCalled()->willReturn('confirmation-token');
        $user->enableAccount()->shouldNotBeCalled();
        $repository->userOfConfirmationToken(new UserToken('confirmation-token'))->shouldBeCalled()->willReturn(null);
        $repository->persist($user)->shouldNotBeCalled();

        $this->shouldThrow(UserTokenNotFoundException::class)->duringExecute($request);
    }
}
