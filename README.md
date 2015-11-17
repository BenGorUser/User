#BenGorUser
> Lightweight user PHP library developed using Domain-Driven Design

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8e36cc04-117a-4448-9cf7-1bedadfbbc59/mini.png)](https://insight.sensiolabs.com/projects/8e36cc04-117a-4448-9cf7-1bedadfbbc59)
[![Build Status](https://travis-ci.org/BenGor/User.svg?branch=master)](https://travis-ci.org/BenGor/User)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/BenGor/User/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/BenGor/User/?branch=master)
[![Total Downloads](https://poser.pugx.org/bengor/user/downloads)](https://packagist.org/packages/bengor/user)
[![Latest Stable Version](https://poser.pugx.org/bengor/user/v/stable.svg)](https://packagist.org/packages/bengor/user)
[![Latest Unstable Version](https://poser.pugx.org/bengor/user/v/unstable.svg)](https://packagist.org/packages/bengor/user)

BenGorUser is **lightweight, flexible and easy to use** user management library. Using **Domain-Driven Design**
this library offers a well tested and documented codebase with many common use cases already implemented, such as:
 
 * User registration with confirmation email
 * Remember password
 * Log in / log out
 * User invitation
 * User removal
 * Change privileges (roles)

##Installation
The easiest way to install this component is using [Composer][7]
```bash
$ composer require bengor/user
```

##Documentation
All the documentation is stored in the `docs` folder.

[Show me the docs!](docs/index.md)

##Tests
This library is completely tested by **[PHPSpec][1], SpecBDD framework for PHP**.

Run the following command to launch tests:
```bash
$ vendor/bin/phpspec run -fpretty
```

##Contributing
This library follows PHP coding standards, so pull requests need to execute the Fabien Potencier's [PHP-CS-Fixer][5]
and Marc Morera's [PHP-Formatter][6]. Furthermore, if the PR creates some not-PHP file remember that you have to put
the license header manually. In order to simplify we provide a Composer script that wraps all the commands related with
this process.
```bash
$ composer run-script cs
```

There is also a policy for contributing to this project. Pull requests must be explained step by step to make the
review process easy in order to accept and merge them. New methods or code improvements must come paired with
[PHPSpec][1] tests.

If you would like to contribute it is a good point to follow Symfony contribution standards, so please read the
[Contributing Code][2] in the project documentation. If you are submitting a pull request, please follow the guidelines
in the [Submitting a Patch][3] section and use the [Pull Request Template][4].

##Credits
This library is created by:
>
**@benatespina** - [benatespina@gmail.com](mailto:benatespina@gmail.com)<br>
**@gorkalaucirica** - [gorka.lauzirika@gmail.com](mailto:gorka.lauzirika@gmail.com)

##Licensing Options
[![License](https://poser.pugx.org/bengor/user/license.svg)](https://github.com/BenGor/User/blob/master/LICENSE)

[1]: http://www.phpspec.net/
[2]: http://symfony.com/doc/current/contributing/code/index.html
[3]: http://symfony.com/doc/current/contributing/code/patches.html#check-list
[4]: http://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request
[5]: http://cs.sensiolabs.org/
[6]: https://github.com/mmoreram/php-formatter
[7]: http://getcomposer.org
