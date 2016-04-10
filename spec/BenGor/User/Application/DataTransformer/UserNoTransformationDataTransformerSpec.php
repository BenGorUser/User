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

namespace spec\BenGor\User\Application\DataTransformer;

use BenGor\User\Application\DataTransformer\UserDataTransformer;
use BenGor\User\Application\DataTransformer\UserNoTransformationDataTransformer;
use BenGor\User\Domain\Model\User;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of UserNoTransformationDataTransformer class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UserNoTransformationDataTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UserNoTransformationDataTransformer::class);
    }

    function it_implements_user_data_transformer()
    {
        $this->shouldImplement(UserDataTransformer::class);
    }

    function it_transforms(User $user)
    {
        $this->read()->shouldReturn([]);
        $this->write($user);
        $this->read()->shouldReturn($user);
    }
}
