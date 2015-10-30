#Basic Usage

##Configuring dependencies
First of all, you need to define the types of repository, encoder and mailer you will be using to make everything work.
Services will use the repository and encoder chosen to handle the request, whereas mailer will be used mainly in the 
event subscribers.

To instantiate the repository and encoder just do it as you would with a normal class:

```php
// You can use any UserRepository implementation you want or implement a new one
$repository = new InMemoryUserRepository();
 
// You can use one of the implemented encoders in Infrastructure/Security folder or create a new one
$encoder = new YourUserPasswordEncoder(); 
```

> You may want to read more about [repositories](repositories.md) and [encoders](encoders.md)

## Using services
This library has a number of use cases that are ready to use. Everything has been abstracted to be as easy as calling 
a service containing the use case you want to use. You just need to create an instance of the service you want 
to use and call `execute()` with your request. All services have the own related request, for example 
`ActivateUserAccountService` has its own `ActivateUserAccountService` and so on.

> All application services and related requests are located under `src/Application/Service` folder

In order to sign up a user for example just do the following:

```php
// Example of $repository and $encoder instantiation above 

$service = new SignUpUserService($repository, $encoder);
$service->execute(new SignUpUserRequest($email, $plainPassword));
```

> Plenty of use cases are available and more detailed info is available at [use cases](use_cases.md) documentation
file

##Subscribing to events
**Important:** In order to receive registration confirmation mail and remember password mail you need to subscribe to the 
[domain events](events.md) triggered by the model. This two subscribers have been already implemented and are 
ready to use

```php
$mailer = new SwiftMailerUserMailer($swiftMailer);
DomainEventPublisher::instance()->subscribe(
    new UserRegisteredMailerSubscriber($mailer, $fromEmail, $body)
);
DomainEventPublisher::instance()->subscribe(
    new UseRememberPasswordRequestSubscriber($mailer, $fromEmail, $body)
);
```
 
> You may want to read more about [events and subscribers](events.md)
