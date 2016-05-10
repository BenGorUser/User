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

## Running commands

BenGorUser relies in a command bus to run use cases against the domain. This library exposes an interface and there are
already some bridges implemented for some of the most used command buses in the PHP community.

To make it work just select the command bus the that fits you better:

* [SimpleBusBridge][1]: `composer require bengoruser/simple-bus-bridge` 
* [TacticianBridge][2]: `composer require bengoruser/tactician-bridge`
    
> You can write your own bridge for your favorite command bus, check [command_bus](command_bus.md) to learn more.
    
Once you have installed the bridge just create an instance of the command bus that will be later used to handle commands.

> Check [SimpleBusBridge getting started docs][3] or [TacticianBridge getting started docs][3] to know how to create a
command bus instance

To run a command just do the following:

```php
$commandBus->handle(new LogInUserCommand('test@bengoruser.con', '123456'));
```

> Plenty of commands are available and more detailed info is available at [command](command.md) documentation
file

##Subscribing to events

**Important:** DomainEventPubliser was removed in v0.6.0, new docs to come. 

In order to receive registration confirmation mail and remember password mail you need to subscribe to
the [domain events](events.md) triggered by the model. `DomainEventPublisher` triggers the subscribers based on domain 
events. To subscribe to those events just do the following:

```php
DomainEventPublisher::instance()->subscribe(
    new YourSubscriber()
);
```
 
> Some subscribers have been already implemented. For more info read about [events and subscribers](events.md)

[1]: https://github.com/BenGorUser/SimpleBusBridge
[2]: https://github.com/BenGorUser/TacticianBridge
[3]: https://github.com/BenGorUser/SimpleBusBridge
[4]: https://github.com/BenGorUser/TacticianBridge
