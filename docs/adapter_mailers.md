#Mailers

The following mailer adapters are available:

* [SwiftmailerBridge](https://github.com/BenGorUser/SwiftMailerBridge)
* [MandrillBridge](https://github.com/BenGorUser/MandrillBridge) 

> In case you have created a new adapter, please send us a Pull Request with a link to your repository.

## About the mailer architecture

The process of sending a mail has been splitted into three diferent responsibilities:

* **The mail information container**: The `UserMailable` class standardizes the usage of the "mail" across the library 
achieving consistency in the usage of this concept.
* **The creation of the mail**: The `UserMailableFactory` interface defines the requirement of the `build` method that
needs to be implemented by different classes responsible of creating instances of `UserMailable`. 
Its useful to generate different mail contents based on parameters received and the different helpers such as template 
engines or route generators.
* **The mail sender**: The `UserMailer` interface defines the way in which all mail sending services need to implement
the logic responsible of sending `UserMailable`.

TL;DR: The mail sending flow works as follows:
* Create a `UserMailable` using an instance of `UserMailableFactory`.
* Use an instance of `UserMailer` to send the `UserMailable` created in the previous step.

##Implement your own mailer

To implement an adapter to use it with your own mailer library just extend the `UserMailer` interface and implement
`mail(UserMailable $userMailable)` to handle the `UserMailable` instance as required.


