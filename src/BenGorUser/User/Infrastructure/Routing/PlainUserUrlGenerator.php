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

namespace BenGorUser\User\Infrastructure\Routing;

use BenGorUser\User\Domain\Model\UserUrlGenerator;

/**
 * Plain user URL generator class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class PlainUserUrlGenerator implements UserUrlGenerator
{
    /**
     * The url.
     *
     * @var string
     */
    private $url;

    /**
     * Constructor.
     *
     * @param string $anUrl The url
     */
    public function __construct($anUrl)
    {
        $this->url = $anUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($aToken)
    {
        return str_replace('{token}', $aToken, $this->url);
    }
}
