# Project hornherzogen

[![Logo](/logo/hornherzogenLogoTransparent.png)]

Pet project to manage seminar registration for Herzogenhorn ...
started in January 2017. Proved to be useful in 2017 :-)
Ready to be used for any consecutive year.

At the moment there seem to be some problem with recent PHP versions.

## Project Status

https://github.com/users/ottlinger/projects/5

[![Average time to resolve an issue](https://isitmaintained.com/badge/resolution/ottlinger/hornherzogen.svg)](https://isitmaintained.com/project/ottlinger/hornherzogen "Average time to resolve an issue")
[![Percentage of issues still open](https://isitmaintained.com/badge/open/ottlinger/hornherzogen.svg)](https://isitmaintained.com/project/ottlinger/hornherzogen "Percentage of issues still open")

[![Join the chat at https://gitter.im/hornherzogen](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/hornherzogen)

[![GPL v3.0](https://img.shields.io/github/license/ottlinger/hornherzogen.svg)](https://www.gnu.org/licenses/gpl.html)

## Github integrations
In order to just play around with it I've integrated the project into various freely available services.

### Travis / CI
[![Build Status](https://travis-ci.org/ottlinger/hornherzogen.svg?branch=master)](https://travis-ci.org/ottlinger/hornherzogen)

### Code coverage
[![codecov](https://codecov.io/gh/ottlinger/hornherzogen/branch/master/graph/badge.svg)](https://codecov.io/gh/ottlinger/hornherzogen)

### Codacy - code quality and static analysis
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c8fc0c6ef3d14192a2a8f84a670ccb92)](https://www.codacy.com/app/github_25/hornherzogen)

## Style CI - code style

[![StyleCI](https://github.styleci.io/repos/78297821/shield?branch=master)](https://github.styleci.io/repos/78297821?branch=master)

## Snyk - dependency management

[![Known Vulnerabilities](https://snyk.io/test/github/ottlinger/hornherzogen/badge.svg?targetFile=composer.lock)](https://snyk.io/test/github/ottlinger/hornherzogen?targetFile=composer.lock)

# Releases

An overview of existing releases can be found [here](RELEASES.md).

## Local installation

### Prerequisites

* PHP >=7.3
  * with XML module installed
  * with MBString extension installed
  * with MySQL extension installed
  * with intl/i18n extension installed
  * (with php-xdebug in case you want local code coverage)
  * (with SQLite extension installed (for tests only)
* Configured Mail server so that PHP may send mails
* Webserver (Apache2)
* MySQL database with tables initialized, see [herzogenhorn.sql](sql/herzogenhorn.sql) for details

### What to change if you want to deploy the application

* Clone the project into your webserver's root directory or download it as ZIP if you do not have a [Git](https://git-scm.com/) properly set up
* Bootstrap the database with the help of [herzogenhorn.sql](sql/herzogenhorn.sql) and your favourite database admin tool
* Copy and adapt the configuration template as [inc/config.ini.php](/inc/config.ini.php.template) with all your credentials
* Adapt the admin area credentials setup and path to AuthUserFile in [.htaccess](admin/.htaccess) and [.htpasswd](admin/.htpasswd)
* Use the project and verify with the help of the test scripts under [admin](admin/index.php) that everything works fine :-)
* Check your logs:
```
$ tail -f /var/log/apache2/*.log
```
### PHP stuff

Local installations work fine with PHP7, while some hosters have trouble because of
* UTF-8 issues
* missing modules such as intl for l10n

#### Ubuntu

You need to install php and add some libraries to your local Webserver:
```
$ sudo apt-get install php7.4-xml php7.4-mbstring php7.4-mysql php7.4-intl
or in case you want to run tests or develop on the project
$ sudo apt-get install php7.4-xml php7.4-mbstring php7.4-mysql php7.4-intl php7.4-sqlite3 php-xdebug sqlite3
$ sudo /etc/init.d/apache2 restart
```

PHPUnit will be installed via composer, see section about development for further details.

#### MacOS

The equivalent installation via homebrew is
```
$ brew install php@7.4 sqlite3
(deprecated: $ brew install php70 php70-intl php70-xdebug sqlite3)
```
to make the tests run locally.
The PHP extension for sqlite seems to be enabled by default.

#### Database Management

##### Create an individual database

If you are using MySQL you may setup your databases locally for easier development:
```
$ sudo apt install mysql
...
You will be asked for the MySQL root password - remember to remember that password!
...
$ mysqladmin create hornherzogen -u root -p
Enter password: // use above freshly created root user

$ mysql -u root -p

// if you want to connect from local
mysql> grant usage on *.* to dbuser@localhost identified by 'yourpasswordhere';
Query OK, 0 rows affected, 1 warning (0,00 sec)

mysql> grant all privileges on hornherzogen.* to dbuser@localhost;
Query OK, 0 rows affected (0,00 sec)

mysql> exit

$ mysql -u dbuser -p'yourpasswordhere' hornherzogen
// successful if you see:
mysql>
```
Do not forget to put above credentials into your inc/config.ini.php file.

##### Graphical database Management

In case you do not have a phpMyAdmin installed, you might want to give
```
$ sudo apt install mysql-workbench
```
a try.

##### Database Bootstrap

You may now use the bootstrap script [herzogenhorn.sql](sql/herzogenhorn.sql)

If you need to remove all tables, use [herzogenhorn_remove.sql](sql/herzogenhorn_remove.sql) with *GREAT CAUTION*!


#### Composer / Dependency Management
In case you want to develop the dependency manager can be installed via:
```
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install
```

You should regularly perform an:
```
$ ./composer.phar self-update
$ ./composer.phar update
```
to get updates.

#### Unittests / PHPUnit

In order to run tests locally you need to run [PHPUnit](https://phpunit.de/getting-started.html):
```
$ phpunit
or
$ phpunit -c phpunit.xml
```

##### Local run

In order to run tests locally (apart from Travis) you may want to configure a handy alias in your `.bashrc`:
```
# PHP aliases
alias phpunit='./vendor/bin/phpunit'
```

This is necessary if you've installed composer project-locally and have no phpunit installed on the system that is in your $PATH!

##### Testdocs

In order to see a nicer test output, launch:
```
$ phpunit --testdox tests
```

### Configuration

The configuration file needs to be created in order to set certain application parameters. The file needs to reside in `inc/config.ini.php` and is not checked in!

| Configuration parameter        | Comment           |
| --- |:---:|
| mail | Complete email address, may contain a subject line as well |
| pdf | Complete link to the seminar announcement (PDF) |
| registrationmail | Email address that all registrations are send to |
| sendregistrationmails | Boolean, whether to send registration mails to customers |
| sendinternalregistrationmails | Boolean, whether to send mails internally upon registration via web form |
| submissionend | Date string after which the application is not possible anymore, format: YYYY-mm-dd |
| dbhost | Database hostname with port if necessary |
| dbname | Database name |
| dbuser | Database username |
| dbpassword | Database password |
| iban | Target account [IBAN](https://en.wikipedia.org/wiki/International_Bank_Account_Number) information |
| bic | Target account [BIC](https://en.wikipedia.org/wiki/ISO_9362) information |
| accountholder | Target account holder name |
| superuser | List of superusers with special permissions in the admin area (comma-separated list as one string) |

See example configurations in
[configuration example of this project](inc/config.ini.php.template)

## Links and tutorials

This a collection of related links that helped working on and with this application:

* [Markdown cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
* [Bootstrap UI framework](https://getbootstrap.com/getting-started/#download) with Starter Template used here
  * [download v3.3.7](https://github.com/twbs/bootstrap/releases/download/v3.3.7/bootstrap-3.3.7-dist.zip)
  * [download with examples v3.3.7](https://github.com/twbs/bootstrap/archive/v3.3.7.zip)
  * [additional snippets for Bootstrap projects](https://bootsnipp.com/)
  * [additional form helpers](https://bootstrapformhelpers.com/) such as language list or datepicker
  * [icon overview](https://glyphicons.bootstrapcheatsheets.com/)
* PHP  
  * [PHPUnit](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html)
  * [PHPUnit - database tests](https://phpunit.de/manual/current/en/database.html)
  * [PHP manual](https://php.net/manual/en/)
  * [Learning PHP-example code](https://github.com/oreillymedia/Learning_PHP)
* books
  * [Learning PHP: A Gentle Introduction to the Web's Most Popular Language, David Sklar](https://www.amazon.de/Learning-PHP-Introduction-Popular-Language/dp/1491933577?tag=tendoryuberlin)
  * [Git Pocket Guide](https://www.amazon.de/Git-Pocket-Guide-Richard-Silverman/dp/1449325866?tag=tendoryuberlin)

## License

* Copyright © 2017-2020 P. Ottlinger, licensed as [GPLv3](LICENSE) ![GPLv3](https://www.gnu.org/graphics/gplv3-88x31.png)
