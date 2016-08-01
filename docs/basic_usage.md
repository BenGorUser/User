#Basic Usage

##Configuring dependencies
First of all, you need to decide the types of repository, encoder and mailer you will be using to make everything work.
Services will use the repository and encoder chosen to handle the command, whereas mailer will be used mainly in the 
event subscribers. These decisions are important because as you will see almost all use cases will require you to pass
some of this dependencies.

To instantiate the repository and encoder just do it as you would with a normal class:
```php
$repository = new \BenGorUser\User\Infrastructure\Persistence\InMemoryUserRepository();
 
$encoder = new \BenGorUser\User\Infrastructure\Security\DummyUserPasswordEncoder('our-strong-pass'); 
```
> You may want to read more about [repositories](adapters_persistence.md) and [encoders](adapters_security.md)

## Running commands
BenGorUser relies in a command bus to run use cases against the domain. This library exposes an interface and there are
already some bridges implemented for some of the most used command buses in the PHP community, [check it out](adapter_bus.md).

Once you have installed the bridge just create an instance of the command bus that will be later used to handle commands.

To run a command just do the following:
> *Remember that the `$commandBus` needs to implement `BenGorUser\User\Infrastructure\CommandBus\UserCommandBus`*

```php
$commandBus->handle(
    new \BenGorUser\User\Application\Command\LogIn\LogInUserCommand(
        'test@bengoruser.con',
        '123456'
    )
);
```
Furthermore, the use of command bus is not mandatory so, you can handle the command instantiate command handler class
directly:
```php

(new BenGorUser\User\Application\Command\LogIn\LogInUserHandler(
    $repository,
    $encoder
))->__invoke(
    new \BenGorUser\User\Application\Command\LogIn\LogInUserCommand(
        'test@bengoruser.con',
        '123456'
    )
);
```
> Plenty of commands are available and more detailed info is available at [command](command.md) documentation file.

##Subscribing to events
In order to receive, for example, a registration confirmation mail, you need to subscribe to the
[domain events](events.md) triggered by the model.

> Some subscribers have been already implemented. For more info read about [events and subscribers](events.md)

> You may want to read more about [mailers](adapters_mailers.md)

- Back to the [index](index.md).
