PHP Interval
======================
[![Downloads](https://poser.pugx.org/chdemko/interval/d/total.png)](https://packagist.org/packages/chdemko/interval)
[![Latest Stable Version](https://poser.pugx.org/chdemko/interval/version.png)](https://packagist.org/packages/chdemko/interval)
[![Latest Unstable Version](https://poser.pugx.org/chdemko/interval/v/unstable.png)](https://packagist.org/packages/chdemko/interval)
[![Code coverage](https://coveralls.io/repos/chdemko/php-interval/badge.png?branch=master)](https://coveralls.io/r/chdemko/php-interval?branch=master)
[![Build Status](https://secure.travis-ci.org/chdemko/php-interval.png)](http://travis-ci.org/chdemko/php-interval)
[![License](https://poser.pugx.org/chdemko/interval/license.png)](http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.html)

Interval for PHP.

This project uses:

* [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer) for checking PHP code style using [Joomla Coding Standards](https://github.com/joomla/coding-standards)
* [PHPUnit](http://phpunit.de/) for unit test (100% covered)
* [phpDocumentor](http://http://www.phpdoc.org/) for api documentation

Installation
------------

Using composer: either

~~~
$ composer create-project chdemko/interval:1.0.x-dev --dev; cd interval
~~~

or create a `composer.json` file containing

~~~json
{
    "require": {
        "chdemko/interval": "1.0.x-dev"
    }
}
~~~
and run
~~~
$ composer install
~~~

Create a `test.php` file containg
~~~php
<?php
require __DIR__ . '/vendor/autoload.php';

use chdemko\Interval\Interval;

$interval = Interval::fromString('[2,3[');

echo $interval . PHP_EOL;

$interval->sup = 4;
echo $interval . PHP_EOL;

$interval->inf = - INF;
echo $interval . PHP_EOL;

if ($interval->contains(0))
{
	echo "0 is contained in $interval" . PHP_EOL;
}
else
{
	echo "0 is not contained in $interval" . PHP_EOL;
}

~~~
This should print
~~~
[2,3[
[2,4[
]-INF,4[
0 is contained in ]-INF,4[
~~~
See the [examples](https://github.com/chdemko/php-interval/tree/master/examples) folder for more information.

Documentation
-------------

* [http://chdemko.github.io/php-interval](http://chdemko.github.io/php-interval)

Citation
--------

If you are using this project including publication in research activities, you have to cite it using ([BibTeX format](https://raw.github.com/chdemko/php-interval/master/cite.bib)). You are also pleased to send me an email to chdemko@gmail.com.
* authors: Christophe Demko
* title: php-interval: a PHP library for handling intervals
* year: 2014
* how published: http://chdemko.github.io/php-interval

