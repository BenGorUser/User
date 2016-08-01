#Bus adapters

The following bus adapters are available:

* [SimpleBusBridge](https://github.com/BenGorUser/SimpleBusBridge)

> In case you have created a new adapter, please send us a Pull Request with a link to your repository.

##About the bus architecture
There are two type of bus inside this package: *UserCommandBus* and *UserEventBus*. Their use is not mandatory but it
is highly recommended.

This buses are required to manage the command and event usage respectively. Some adapters have been
already created for a plug and play usage. In case you have some specific requirement you can implement your own adapter.

##Implement your own command bus
To implement your own command bus you need to implement `BenGorUser\User\Infrastructure\CommandBus\UserCommandBus` interface method
`handle()`.

##Implement your own event bus
To implement your own event bus you need to implement `BenGorUser\User\Infrastructure\Domain\Model\UserEventBus` interface method
`handle()`.

- Back to the [index](index.md).
