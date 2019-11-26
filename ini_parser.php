#!/usr/bin/env php
<?php

/**
    * Parse php.ini files (CLI / Apache) for active settings.
    *
    * Usage:
    *        php ini_parser <file> | [-l]
    *
    * @author         Martin Latter
    * @copyright      Martin Latter 23/11/2019
    * @version        0.01
    * @license        GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
    * @link           https://github.com/Tinram/Utilities.git
*/


define('DUB_EOL', PHP_EOL . PHP_EOL);

$sUsage =
    PHP_EOL . ' ' . basename(__FILE__, '.php') .
    DUB_EOL . "\tusage: php " . basename(__FILE__) . ' <file> | [-l]' . DUB_EOL;

if ( ! isset($_SERVER['argv'][1]))
{
    echo PHP_EOL . ' missing file path' . PHP_EOL;
    die($sUsage);
}

if ($_SERVER['argv'][1] === '-l')
{
    echo ' ---- CLI ----' . PHP_EOL;
    system('php --ini');
    echo ' ---- APACHE ----' . PHP_EOL;
    system('locate php.ini');
    exit;
}

$aSettings = parse_ini_file($_SERVER['argv'][1]);

echo PHP_EOL;

foreach ($aSettings as $k => $v)
{
    if (empty($v)) {continue;}

    echo ' ' . $k . ' : ' . $v . PHP_EOL;
}

echo PHP_EOL;
