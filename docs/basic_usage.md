#Basic Usage

##Configuring dependencies
First of all, you need to decide the types of repository, encoder and mailer you will be using to make everything work.
Services will use the repository and encoder chosen to handle the request, whereas mailer will be used mainly in the 
event subscribers. These decisions are important because as you will see almost all use cases will require you to pass
some of this dependencies.

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
a service containing the use case you need. Just create an instance of the service you want 
to use and call `execute()` with your request. All services have the own related request, for example 
`ActivateUserAccountService` has its own `ActivateUserAccountRequest` and so on.

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
[domain events](events.md) triggered by the model. `DomainEventPublisher` triggers the subscribers based on domain 
events. To subscribe to those events just do the following:

```php
DomainEventPublisher::instance()->subscribe(
    new YourSubscriber()
);
```
 
> Some subscribers have been already implemented. For more info read about [events and subscribers](events.md)
