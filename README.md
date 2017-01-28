# Project hornherzogen
Pet project to manage seminar registration for Herzogenhorn 2017

Status: Work in progress :-)

2017-01-07

## Travis integration

In order to just play around with it I've integrated a CI run:

[![Build Status](https://travis-ci.org/ottlinger/hornherzogen.svg?branch=master)](https://travis-ci.org/ottlinger/hornherzogen)

[![codecov](https://codecov.io/gh/ottlinger/hornherzogen/branch/master/graph/badge.svg)](https://codecov.io/gh/ottlinger/hornherzogen)

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c8fc0c6ef3d14192a2a8f84a670ccb92)](https://www.codacy.com/app/github_25/hornherzogen)

## Local installation

### Prerequisites

* PHP >=5.6, since my hoster does not properly support 7.x
  * with XML module installed
  * with MBString extension installed
  * with MySQL extension installed
* Configured Mail server so that PHP may send mails
* Webserver (Apache2)
* MySQL database with tables initialized

### PHP stuff

You need to install php and add some libraries to your local Webserver:
```
$ sudo apt install phpunit php7.0-xml php7.0-mbstring php7.0-mysql
$ sudo /etc/init.d/apache2 restart
```

#### Composer

The dependency manager can be installed
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
