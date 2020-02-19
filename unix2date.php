#!/usr/bin/env php
<?php

/**
    * unix2date
    *
    * Convert UNIX timestamp into local time.
    *
    * Usage:
    *        php unix2date.php <timestamp>
    *
    * @author         Martin Latter
    * @copyright      Martin Latter 26/06/2018
    * @version        0.03
    * @license        GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
    * @link           https://github.com/Tinram/Utilities.git
*/


declare(strict_types=1);

define('DUB_EOL', PHP_EOL . PHP_EOL);

$sUsage =
    PHP_EOL . ' ' . basename(__FILE__, '.php') .
    DUB_EOL . "\tusage: php " . basename(__FILE__) . ' <timestamp>' . DUB_EOL;

if ( ! isset($_SERVER['argv'][1]))
{
    echo PHP_EOL . ' missing timestamp!' . PHP_EOL;
    die($sUsage);
}

$iTS = abs((int) $_SERVER['argv'][1]);

if ($iTS > 0)
{
    echo PHP_EOL . ' timestamp is not a number!' . PHP_EOL;
    die($sUsage);
}

echo ' ' . date('d M Y, H:i:s P T', $iTS) . PHP_EOL;
