#Persistence adapters

The following persistence adapters are available:

*  [DoctrineORMBridge](https://github.com/BenGorUser/DoctrineORMBridge)
*  [DoctrineODMMongoDBBridge](https://github.com/BenGorUser/DoctrineODMMongoDBBridge)
*  InMemorylUserRepository that is available in this library
*  SqlUserRepository that is available in this library

> In case you have created a new adapter, please send us a Pull Request with a link to your repository.

##About the repository architecture
User is persisted in each service to save changes done in that process. Doctrine, InMemory and Sql
adapters have been implemented for a plug and play usage.

All this repositories implement `BenGor\User\Domain\Model\UserRepository` interface. In case you have
some specific requirement you can implement your own adapter.
