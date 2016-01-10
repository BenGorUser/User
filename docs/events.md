#Domain events

Domain events are triggered during the execution of the use cases. This allows hooking into the use case to 
perform actions based on the event triggered with many possibilities.

The following actions are triggered:

* `BenGor\User\Domain\Model\Event\UserEnabled`: After user has been enabled successfully
* `BenGor\User\Domain\Model\Event\UserInvited`: After user guest has been registered or after the invitation token is regenerated
* `BenGor\User\Domain\Model\Event\UserLoggedIn`: After successful login
* `BenGor\User\Domain\Model\Event\UserLoggedOut`: After logout
* `BenGor\User\Domain\Model\Event\UserRegistered`: After user registered successfully
* `BenGor\User\Domain\Model\Event\UserRememberPasswordRequested`: After remember password has been requested by the user

##Listening to the events

This events are ignored as **no subscribers are enabled by default**. In order to listen to the events you need to register
the subscribers using the `DomainEventPublisher`

> `DomainEventPublisher` is a event bus 

```php
DomainEventPublisher::instance()->subscribe(
    new YourSubscriber()
);
```

##Predefined subscribers

Some ready to use subscribers have been defined based in common use cases such as email sending after some events occur.
All these subscribers are located under `BenGor\User\Infrastructure\Subscriber` namespace.

###Send mail after user invitation

`UserInvitedMailerSubscriber` sends an email to the invited user with the token and instructions to register into the 
application. An example of usage using Symfony subscriber with Twig and Swiftmailer:

```php
$loader = new Twig_Loader_Filesystem('path_to_infrastructure_folder/Ui/Twig/views');
$twig = new Twig_Environment($loader);
$factory = new TwigUserMailableFactory($twig, 'Email/invite.html.twig', 'no-reply@domain.com');
$mailer = new SwiftMailerUserMailer($swiftmailer); //Check swiftmailers docs
DomainEventPublisher::instance()->subscribe(
    new \BenGor\User\Infrastructure\Domain\Event\Symfony\UserInvitedMailerSubscriber($mailer, $factory, $router, 'sign_up_by_invitation_path')
);
```

###Send mail after user is registered

`UserRegisteredMailerSubscriber` sends an email to the registered user with the token and instructions to login into the 
application. An example of usage using Symfony subscriber with Twig and Swiftmailer:

```php
$loader = new Twig_Loader_Filesystem('path_to_infrastructure_folder/Ui/Twig/views');
$twig = new Twig_Environment($loader);
$factory = new TwigUserMailableFactory($twig, 'Email/register.html.twig', 'no-reply@domain.com');
$mailer = new SwiftMailerUserMailer($swiftmailer); //Check swiftmailers docs
DomainEventPublisher::instance()->subscribe(
    new UserRegisteredMailerSubscriber($mailer, $factory, $router, 'activate_user_route'))
);
```

###Send mail with remember password instructions

`UserRememberPaswordRequestSubscriber` send an email to a user that requested a remember password token. 
An example of usage using Symfony subscriber with Twig and Swiftmailer:

```php
$loader = new Twig_Loader_Filesystem('path_to_infrastructure_folder/Ui/Twig/views');
$twig = new Twig_Environment($loader);
$factory = new TwigUserMailableFactory($twig, 'Email/remember_password.html.twig', 'no-reply@domain.com');
$mailer = new SwiftMailerUserMailer($swiftmailer); //Check swiftmailers docs
DomainEventPublisher::instance()->subscribe(
    new UserRememberPaswordRequestSubscriber($mailer, $factory, $router, 'change_user_password_path'))
);
```

##Implement your own subscriber

First of all you need to create a new class that implements `Ddd\Domain\DomainEventSubscriber` interface. This interface
has to methods that you will need to implement `isSubscribedTo()` and `handle()`. The first method will determine if 
our subscriber is listening to the event triggered by the bus. For example, if we want to listen to the user enable 
event we will define the following: 

```php
    public function isSubscribedTo($aDomainEvent)
    {
        return $aDomainEvent instanceof BenGor\User\Domain\Model\Event\UserEnabled;
    }
```

> The whole list of available events is available at the beginning of this page

The `handle()` method on the other side will implement the logic we want to execute once the event has happened.

The last but not least, register your subscriber as explained above in the "Listening to the events" section.

> You may want to read more about [mailers](mailers.md)

