# Project hornherzogen
Pet project to manage seminar registration for Herzogenhorn 2017

Status: Work in progress :-)

[![Stories in Ready](https://badge.waffle.io/ottlinger/hornherzogen.png?label=ready&title=Ready)](https://waffle.io/ottlinger/hornherzogen)


2017-01-07

## Github integration
### Travis / CI

In order to just play around with it I've integrated a CI run:

[![Build Status](https://travis-ci.org/ottlinger/hornherzogen.svg?branch=master)](https://travis-ci.org/ottlinger/hornherzogen)

### Code coverage

[![codecov](https://codecov.io/gh/ottlinger/hornherzogen/branch/master/graph/badge.svg)](https://codecov.io/gh/ottlinger/hornherzogen)

### Codacy - code quality and static analysis

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c8fc0c6ef3d14192a2a8f84a670ccb92)](https://www.codacy.com/app/github_25/hornherzogen)

### VersionEye - dependency update management

[![Dependency versions](https://www.versioneye.com/user/projects/58978d3ea35eb6002e873a36/badge.svg)](https://www.versioneye.com/user/projects/58978d3ea35eb6002e873a36?child=summary)

## Local installation

### Prerequisites

* PHP >=5.6, since my hoster does not properly support 7.x yet
  * with XML module installed
  * with MBString extension installed
  * with MySQL extension installed
  * with intl/i18n extension installed
  * (with php-xdebug in case you want local code coverage)
* Configured Mail server so that PHP may send mails
* Webserver (Apache2)
* MySQL database with tables initialized, see [herzogenhorn.sql](herzogenhorn.sql) for details

### What to change if you want to deploy the application

* Clone the project into your webserver's root directory or download it as ZIP if you do not have a [Git](https://git-scm.com/) properly set up
* Bootstrap the database with the help of [herzogenhorn.sql](herzogenhorn.sql) and your favourite database admin tool
* Copy and adapt the configuration template as [inc/config.ini.php](/inc/config.ini.php.template) with all your credentials
* Adapt the admin area credentials setup and path to AuthUserFile in [.htaccess](admin/.htaccess) and [.htpasswd](admin/.htpasswd)
* Use the project and verify with the help of the test scripts under [admin](admin/index.php) that everything works fine :-)
* Check your logs:
```
$ tail -f /var/log/apache2/*.log
```
### PHP stuff

#### Ubuntu

You need to install php and add some libraries to your local Webserver:
```
$ sudo apt install phpunit php7.0-xml php7.0-mbstring php7.0-mysql php7.0-intl
or
$ sudo apt install phpunit php7.0-xml php7.0-mbstring php7.0-mysql php7.0-intl php-xdebug
$ sudo /etc/init.d/apache2 restart
```

#### MacOS

The equivalent installation via homebrew is
```
$ brew install php70 php70-intl
```
to make the tests run locally.

#### Composer / Dependency Management
In case you want to develop the dependency manager can be installed
```
$ curl -s http://getcomposer.org/installer | php
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
| mail | complete email address, may contain a subject line as well |
| pdf | complete link to the seminar announcement (PDF) |
| registrationmail | email address that all registrations are send to |
| sendregistrationmails | boolean, whether to send registration mails to customers |
| sendinternalregistrationmails | boolean, whether to send mails internally upon registration via web form |
| dbhost | database hostname with port if necessary |
| dbname | database name |
| dbuser | database username |
| dbpassword | database password |

See example configurations in
[configuration example of this project](inc/config.ini.php.template)

## Links

This a collection of related links:

* [PHPUnit](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html)
* [PHP manual](http://php.net/manual/en/)
* book: [Learning PHP: A Gentle Introduction to the Web's Most Popular Language, David Sklar](https://www.amazon.de/Learning-PHP-Introduction-Popular-Language/dp/1491933577?tag=tendoryuberlin)
* [Learning PHP-example code](https://github.com/oreillymedia/Learning_PHP)

## License

* [GPLv3](LICENSE)
