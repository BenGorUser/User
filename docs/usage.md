```php
<?php
// cli-config.php
require_once "foo.php";

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
```

```php
// foo.php

#!/usr/bin/env php
<?php

use BenGorUser\User\Domain\Event\UserRegisteredMailerSubscriber;

require_once "vendor/autoload.php";

// database configuration parameters
$conn = [
    'dbname'   => 'bengor_user_carlosbuenosvinos',
    'user'     => 'root',
    'password' => '',
    'host'     => 'localhost',
    'driver'   => 'pdo_mysql',
];

// obtaining the entity manager
$entityManager = (new \BenGorUser\DoctrineORMBridge\Infrastructure\Persistence\EntityManagerFactory())->build($conn);


$service = new \BenGorUser\CarlosBuenosvinosDddBridge\Application\Service\SignUp\SignUpUserService(
    new \BenGorUser\User\Application\Command\SignUp\SignUpUserHandler(
        new \BenGorUser\DoctrineORMBridge\Infrastructure\Persistence\DoctrineORMUserRepository(
            $entityManager,
            $entityManager->getClassMetadata(\BenGorUser\CarlosBuenosvinosDddBridge\Domain\Model\User::class)
        ),
        new \BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder('123456'),
        new \BenGorUser\User\Infrastructure\Domain\Model\UserFactorySignUp(
            \BenGorUser\CarlosBuenosvinosDddBridge\Domain\Model\User::class
        )
    )
);

$loader = new Twig_Loader_Filesystem(__DIR__ . '/vendor/bengor-user/twig-bridge/src/BenGorUser/TwigBridge/Infrastructure/Ui/Twig/views');

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('bengor_user_user_homepage', new Route('/'));

$context = new RequestContext('/');

$generator = new UrlGenerator($routes, $context);

$url = $generator->generate('bengor_user_user_homepage');

\Ddd\Domain\DomainEventPublisher::instance()->subscribe(
    new \BenGorUser\CarlosBuenosvinosDddBridge\Domain\Event\UserEventSubscriber(
        new UserRegisteredMailerSubscriber(
        new \BenGorUser\SwiftMailerBridge\Infrastructure\Mailing\SwiftMailerUserMailer(
            new Swift_Mailer(new Swift_SmtpTransport())
        ),
        new \BenGorUser\TwigBridge\Infrastructure\Mailing\TwigUserMailableFactory(
            new \Twig_Environment($loader),
            'Email/sign_up.html.twig',
            'benatespina@gmail.com'
        ),
        new \BenGorUser\SymfonyRoutingBridge\Infrastructure\Routing\SymfonyUserUrlGenerator(
            new \Symfony\Component\Routing\Generator\UrlGenerator(
                $routes, $context
            )
        ),
        'bengor_user_user_homepage'
    ))
);

$command = new \BenGorUser\User\Application\Command\SignUp\SignUpUserCommand('benatespina@gmail.com', '123456', ['ROLE_USER']);

(new \Ddd\Application\Service\TransactionalApplicationService(
    $service,
    new \Ddd\Infrastructure\Application\Service\DoctrineSession($entityManager)
))->execute($command);

var_dump('ok');die;

```

> * Mapping of User fails because needs `BenGorUser.CarlosBuenosvinosDddBridge.Domain.Model.User.dcm` instead of
`BenGorUser.User.Domain.Model.User.dcm`
