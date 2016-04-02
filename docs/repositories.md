#User Repository

User is persisted in each service to save changes done in that process. Doctrine, InMemory and Sql
adapters have been implemented for a plug and play usage.

All this repositories implement `BenGor\User\Domain\Model\UserRepository` interface. In case you have
some specific requirement you can implement your own adapter.

## Doctrine repository usage
#### ORM
TODO
#### ODM - MongoDB
TODO
##InMemory repository usage
TODO
##Sql repository usage
TODO

##Non official repositories

* No other user repositories submitted yet

> Create a PR in case you want to list your own user repository here
