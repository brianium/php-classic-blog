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

