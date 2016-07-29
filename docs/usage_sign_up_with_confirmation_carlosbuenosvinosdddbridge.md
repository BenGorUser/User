#How to sign up user with confirmation email using CarlosBuenosvinosDddBridge

The following code builds all the sign up with confirmation email process.

Please before to execute this script, be sure you have installed the following bengor user bridges:
* bengor-user/carlosbuenosvinos-ddd-bridge
* bengor-user/doctrine-orm-bridge
* bengor-user/swift-mailer-bridge
* bengor-user/symfony-routing-bridge
* bengor-user/twig-bridge

Then you have to create the database with `bengor_user_carlosbuenosvinosdddbridge` name.
> This snippet takes in mind that the database user is `root` and database password is `null`,
> if it is not, you have to change with according values the `$connection` array.

```php
#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$connection = [
    'dbname'   => 'bengor_user_carlosbuenosvinosdddbridge',
    'user'     => 'root',
    'password' => '',
    'host'     => 'localhost',
    'driver'   => 'pdo_mysql',
];
$entityManager = (new \BenGorUser\DoctrineORMBridge\Infrastructure\Persistence\EntityManagerFactory())->build(
    $connection,
    [__DIR__ . '/vendor/bengor-user/carlosbuenosvinos-ddd-bridge/src/BenGorUser/CarlosBuenosvinosDddBridge/Infrastructure/Persistence/Doctrine/ORM/Mapping']
);
$tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
$classes = [
    $entityManager->getClassMetadata(\BenGorUser\CarlosBuenosvinosDddBridge\Domain\Model\User::class),
];
$tool->updateSchema($classes);


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

$resourcesPath = __DIR__ . '/vendor/bengor-user/twig-bridge/src/BenGorUser/TwigBridge/Infrastructure/Ui';

$translator = new \Symfony\Component\Translation\Translator('en_EN');
$translator->addLoader('xlf', new \Symfony\Component\Translation\Loader\XliffFileLoader());
$translator->addResource('xlf', $resourcesPath . '/Translations/BenGorUser.es.xlf', 'es_ES', 'BenGorUser');
$translator->addResource('xlf', $resourcesPath . '/Translations/BenGorUser.en.xlf', 'en_EN', 'BenGorUser');
$translator->addResource('xlf', $resourcesPath . '/Translations/BenGorUser.eu.xlf', 'eu_ES', 'BenGorUser');

$loader = new Twig_Loader_Filesystem($resourcesPath . '/Twig/views');
$twig = new \Twig_Environment($loader);
$twig->addExtension(
    new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator)
);


$routes = new \Symfony\Component\Routing\RouteCollection();
$routes->add('bengor_user_user_homepage', new \Symfony\Component\Routing\Route('/'));
$context = new \Symfony\Component\Routing\RequestContext('/');
$generator = new \Symfony\Component\Routing\Generator\UrlGenerator($routes, $context);
$url = $generator->generate('bengor_user_user_homepage');


\Ddd\Domain\DomainEventPublisher::instance()->subscribe(
    new \BenGorUser\CarlosBuenosvinosDddBridge\Domain\Event\UserEventSubscriber(
        new \BenGorUser\User\Domain\Event\UserRegisteredMailerSubscriber(
            new \BenGorUser\SwiftMailerBridge\Infrastructure\Mailing\SwiftMailerUserMailer(
                new Swift_Mailer(
                    (new Swift_SmtpTransport(
                        'smtp.gmail.com', 465, 'ssl'
                    ))->setUsername('CHANGE-FOR-YOUR-MAILER-USERNAME')->setPassword('CHANGE-FOR-YOUR-MAILER-PASSWORD')
                )
            ),
            new \BenGorUser\TwigBridge\Infrastructure\Mailing\TwigUserMailableFactory(
                $twig,
                'Email/sign_up.html.twig',
                'benatespina@gmail.com'
            ),
            new \BenGorUser\SymfonyRoutingBridge\Infrastructure\Routing\SymfonyUserUrlGenerator(
                new \Symfony\Component\Routing\Generator\UrlGenerator(
                    $routes,
                    $context
                )
            ),
            'bengor_user_user_homepage'
        )
    )
);


(new \Ddd\Application\Service\TransactionalApplicationService(
    $service,
    new \Ddd\Infrastructure\Application\Service\DoctrineSession($entityManager)
))->execute(
    new \BenGorUser\User\Application\Command\SignUp\SignUpUserCommand(
        'benatespina@gmail.com',
        '123456',
        ['ROLE_USER']
    )
);

die('The sign up process is successfully done!');
```
