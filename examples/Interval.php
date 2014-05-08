<?php

/**
 * Interval example
 *
 * @package    Interval
 *
 * @author     Christophe Demko <chdemko@gmail.com>
 * @copyright  Copyright (C) 2012-2014 Christophe Demko. All rights reserved.
 *
 * @license    http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.html The CeCILL B license
 *
 * This file is part of the php-interval package https://github.com/chdemko/php-interval
 */

require __DIR__ . '/../vendor/autoload.php';

use chdemko\Interval\Interval;

$interval = Interval::fromString('[2,3[');

// Print [2,3[
echo $interval . PHP_EOL;

// Print [2,4[
$interval->sup = 4;
echo $interval . PHP_EOL;

// Print ]-INF,4[
$interval->inf = - INF;
echo $interval . PHP_EOL;

// Print 0 is contained in ]-INF,4[
if ($interval->contains(0))
{
	echo "0 is contained in $interval" . PHP_EOL;
}
else
{
	echo "0 is not contained in $interval" . PHP_EOL;
}
