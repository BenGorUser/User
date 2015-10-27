#Domain events

Domain events are triggered during the execution of the use cases. This allows hooking into the use case to 
perform actions based on the event triggered with many possibilities.

The following actions are triggered:

* `BenGor\User\Domain\Model\Event\UserEnabled`: After user has been enabled successfully
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
