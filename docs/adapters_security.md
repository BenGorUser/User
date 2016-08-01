#Security adapters

The following encoder adapters are available:

* [SymfonySecurityBridge](https://github.com/BenGorUser/SymfonySecurityBridge)
*  DummyUserPasswordEncoder that is available in this library

> In case you have created a new adapter, please send us a Pull Request with a link to your repository.

##About the encoder architecture
An Encoder is required by `UserPassword` value object to create an encoded password from a given plain password. As many
strategies exist, an interface (`BenGor\User\Domain\Model\UserPasswordEncoder`) is available. Some adapters have been
already created for a plug and play usage. In case you have some specific requirement you can implement your own adapter.

##Implement your own encoder
To implement your own encoder you need to implement `BenGorUser\User\Domain\Model\UserPasswordEncoder` interface methods
`encode()` and `isPasswordValid()`

- Back to the [index](index.md).
