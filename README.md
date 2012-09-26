A Classic PHP Blog Built With TDD and DDD
=========================================

The purpose of this project was to build a simple blogging tool with the principles of test driven development (TDD), and domain driven design (DDD).

This project is designed to be a good showcase of TDD and DDD. It has a comprehensive test suite, and a layered architecture that should encourage easy extension.

This project was built using PHP 5.4, and it uses composer to manage dependencies.

Getting Things Running
----------------------

### Installing Software Dependencies ###

The dependencies needed for this application are listed in the composer.json file. To install them simply run the following at the command line:

`php composer.phar install`

PHPUnit was installed using PEAR, and it is necessary for running tests.

### PHP Dependencies ###

This application relies on pdo_sqlite for the test suite, and pdo_mysql for production. These are utilized by the Doctrine2 ORM. Several tests will fail if APC is not installed, but the application can run without it. This of course means you need sqlite and mysql installed as well.


### Running The Tests ###

Once all the dependencies are taken care of, you can run the full test suite by running the following shell script in from the bin directory

`bin/runtests`

This script was built using the default Ubuntu bin/bash, and is just a helper for running all tests in the src/Test directory.

If you have guard and guard-phpunit installed, you can run the `guard` command from the terminal for live feedback during development

### Running The Application ###

The application relies on mysql. The credentials used to connect are stored in src/Infrastructure/Persistence/Doctrine/doctrine.cfg.json. Keep in mind, you will need to create a database for the application to run.

This repo comes with a Doctrine console in the bin directory so you can generate the schema. Run the following command to create the schema for the app:

`bin/doctrine orm:schema-tool:create`

If you need to regenerate the proxies (recommended) for the domain entities, run the following:

`bin/doctrine orm:generate-proxies`

The application can be run out of the box using PHP 5.4's built in web server. Running the following command will start a web server on port 8000.

`bin/runapp`

Application Architecture
------------------------

All source for the application is contained in the src directory. The lib directory is where dependencies are installed to via composer.

### Domain ###

The domain layer is the application core. The `Domain\Entities` namespace contains the models used for this application, that is User, Post, and Comment. 

`Domain\Repositories` contains the interfaces for the each entity's repository. These interfaces are realized in the Infrastructure layer.

The root `Domain` namespace contains any value objects and services that are used throughout the application.

### Infrastructure ###

All repositories in this layer rely on Doctrine2, and are held within the `Infrastructure\Persistence\Doctrine` namespace. This layer also contains a `UnitOfWork`

The `ConfigurationFactory` can be used to tweak development and production environments for Doctrine2

### Presentation ###

The presentation layer is built using the Slim framework. The framework was chosen specifically to stay away from an MVC approach. This may better illustrate using the underlying services of the `Domain` and `Infrastructure` packages.

The Twig templating engine was used to generate markup, and Twitter bootstrap was used to give some generic style and structure to the interface.

Fuel Validation powers the input models in this layer.

Slim form based authentication is accomplished using several domain and infrastructure services via the `Presentation\SlimAuthenticationService` service.

### Test ###

Tests are broken into unit and integration tests. Both are constructed using PHPUnit, and were used to drive the implementations for this application.

Objects used throughout different tests can be found in the `Test\Fixtures` namespace.

### bootstrap.php ###

This file loads the composer autoloader, and adds additional classes to the path. It is included in the `Presentation` and `Test` packages.