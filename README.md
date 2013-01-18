Testing PHPUnit's PHPUnit_Extensions_Selenium2TestCase With ParaTest
====================================================================

This is an example of running Selenium Web Driver tests in parallel with ParaTest.
The tests use the Chrome browser to test the Backbone.js Todo app. Running on Ubuntu 12.04
with Chrome Version 22.0.1229.79 - the results were as follows:

Vanilla PHPUnit:

![PHPUnit Selenium Results](https://raw.github.com/brianium/paratest-selenium/master/phpunit-results.jpg "PHPUnit Selenium Results")

ParaTest Results:

![ParaTest Selenium Results](https://raw.github.com/brianium/paratest-selenium/master/paratest-results.jpg "ParaTest Selenium Results")

2.25 times faster than PHPUnit alone on this test and this machine.

Setting Up
----------

### Get Selenium Server ###
You will need to make sure you have selenium server installed. Head over to [SeleniumHQ](http://seleniumhq.org/download/) and download the latest and greatest. To run the server run the jar like so:

`java -jar /path/to/selenium-server-standalone-2.25.0.jar`

### Install Dependencies Via Composer ###
All of the dependencies can be installed via composer.

`php composer.phar install`

This will install PHPUnit and ParaTest.

### Install Chrome Driver ###
These tests operate via the Chrome Driver. Installation is easy, and instructions for doing so can be found at the [Selenium wiki](http://code.google.com/p/selenium/wiki/ChromeDriver)

Running The Tests
-----------------

To run the tests using ParaTest run the following command (will read suite from phpunix.xml):
`vendor/bin/paratest -f`

To run the tests using phpunit alone run the following command:
`vendor/bin/phpunit`

For more information on ParaTest head over to the [repo](https://github.com/brianium/paratest).
