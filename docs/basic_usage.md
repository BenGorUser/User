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

> You may want to read more about [repositories](adapters_persistence.md) and [encoders](adapters_security.md)

## Running commands

BenGorUser relies in a command bus to run use cases against the domain. This library exposes an interface and there are
already some bridges implemented for some of the most used command buses in the PHP community, [check it out](adapter_bus.md).

Once you have installed the bridge just create an instance of the command bus that will be later used to handle commands.

To run a command just do the following:

```php
$commandBus->handle(new LogInUserCommand('test@bengoruser.con', '123456'));
```

> Plenty of commands are available and more detailed info is available at [command](command.md) documentation file.

##Subscribing to events

In order to receive registration confirmation mail, remember password mail or invitation email, you need to subscribe to
the [domain events](events.md) triggered by the model.
 
> Some subscribers have been already implemented. For more info read about [events and subscribers](events.md)
