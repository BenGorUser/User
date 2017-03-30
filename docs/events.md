# Domain events
Domain events are triggered during the execution of the use cases.
This allows hooking into the use case to perform actions based on the
event triggered with many possibilities.

The following actions are triggered:
* `BenGorUser\User\Domain\Model\Event\UserEnabled`: After user has been enabled successfully
* `BenGorUser\User\Domain\Model\Event\UserInvited`: After user has been invited or after the invitation token is regenerated
* `BenGorUser\User\Domain\Model\Event\UserLoggedIn`: After successful login
* `BenGorUser\User\Domain\Model\Event\UserLoggedOut`: After logout
* `BenGorUser\User\Domain\Model\Event\UserRegistered`: After user registered successfully
* `BenGorUser\User\Domain\Model\Event\UserRememberPasswordRequested`: After remember password has been requested
* `BenGorUser\User\Domain\Model\Event\UserRoleGranted`: After a role is granted to a user
* `BenGorUser\User\Domain\Model\Event\UserRoleRevoked`: After a role is revoked to a user

## Listening to the events
Is responsibility of the [bus adapter](adapters_bus.md) to handle
events. Refer to the adapter documentation to get more info about
listening to events.

## Predefined subscribers
Some ready to use subscribers have been defined based in common use
cases such as email sending after some events occur. All these
subscribers are located under `BenGorUser\User\Domain\Event` namespace.

Existing subscribers require de following parameters in their constructors:
* UserMailer: An implementation of a [mailer adapter](adapters_mailers.md) 
* UserMailableFactory: An implementation of a [UI adapter](adapters_ui.md),
* UserUrlGenerator: An implementation of a [routing adapter](adapters_routing.md),

## Implement your own subscriber
First of all you need to create a new class that implements
`BenGorUser\User\Domain\Event\UserEventSubscriber` interface. This interface
has two methods that you will need to implement `isSubscribedTo()` and `handle()`.
The first method will determine if our subscriber is listening to the
event triggered by the bus. For example, if we want to listen to the
user enable event we will define the following: 
```php
public function isSubscribedTo($aDomainEvent)
{
    return $aDomainEvent instanceof BenGor\User\Domain\Model\Event\UserEnabled;
}
```
> The whole list of available events is available at the beginning of this page

The `handle()` method on the other side will implement the logic we want
to execute once the event has happened.

Last but not least, register your subscriber as explained above in the
adapter's "Listening to the events" section.

- Back to the [index](index.md).
