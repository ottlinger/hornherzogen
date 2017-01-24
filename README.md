# Project hornherzogen
Pet project to manage seminar registration for Herzogenhorn 2017

Status: Work in progress :-)

2017-01-07

## Travis integration

In order to just play around with it I've integrated a CI run:

[![Build Status](https://travis-ci.org/ottlinger/hornherzogen.svg?branch=master)](https://travis-ci.org/ottlinger/hornherzogen)

[![codecov](https://codecov.io/gh/ottlinger/hornherzogen/branch/master/graph/badge.svg)](https://codecov.io/gh/ottlinger/hornherzogen)

[![Codacy Badge](https://api.codacy.com/project/badge/grade/ab19f8aeeb264e0bbad1740e07a765aa)](https://www.codacy.com/app/github_25/hornherzogen)

## Local installation

### Prerequisites

* PHP7.x
* Configured Mail server so that PHP may send mails
* Webserver
* MySQL database with tables initialized

### PHP stuff

You need to install php and some libraries:
```
$ sudo apt install phpunit php7.0-xml
```

#### Composer

The dependency manager can be installed
```
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

#### Unittests / PHPUnit

In order to run tests locally you need to run PHPUnit:
```
$ phpunit
or
$ phpunit -c phpunit.xml
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
