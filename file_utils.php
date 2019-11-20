#!/usr/bin/env php
<?php

/**
    * File Utils
    *
    * File character replacement utility, circumventing dos2unix.
    *
    * Usage:
    *        php file_utils.php <filename>
    *
    * @author         Martin Latter
    * @copyright      Martin Latter 01/04/2013
    * @version        0.04
    * @license        GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
    * @link           https://github.com/Tinram/utilities.git
*/


define('DUB_EOL', PHP_EOL . PHP_EOL);


if ( ! isset($_SERVER['argv'][1]) || ! isset($_SERVER['argv'][2]))
{
    die(
        PHP_EOL . ' ' .
        basename($_SERVER['argv'][0], '.php') . DUB_EOL .
        "\tusage: " . basename($_SERVER['argv'][0], '.php') . ' <array|para|rmret|rmtab|rmspc|CRLF|LF|bm> <filename>' . DUB_EOL
    );
}

$sFilename = $_SERVER['argv'][2];

if ( ! file_exists($sFilename))
{
    die(PHP_EOL . " '$sFilename' does not exist in this directory!" . DUB_EOL);
}

$sAction = strtolower($_SERVER['argv'][1]);

$sContents = file_get_contents($sFilename);

if ( ! $sContents)
{
    die(PHP_EOL . " Error reading file: '$sFilename'" . DUB_EOL);
}


switch ($sAction)
{
    case 'array': # line items quoted for array
        $sContents = str_replace("\r\n", "\n", $sContents); # Windows replace
        $sContents = str_replace("\n", "',\n'", $sContents);
        break;

    case 'para': # double spaced items into HTML paragraphs
        $sContents = str_replace("\r\n", "\n", $sContents);
        $sContents = str_replace("\n\n", "</p>\n\n<p>", $sContents);
        break;

    case 'rmret': # remove return characters
        $sContents = str_replace("\r\n", '', $sContents);
        $sContents = str_replace("\n", '', $sContents);
        break;

    case 'rmtab': # remove tab characters
        $sContents = str_replace("\t", '', $sContents);
        break;

    case 'rmspc': # remove space characters
        $sContents = str_replace(' ', '', $sContents);
        break;

    case 'crlf': # LF to CRLF
        $sContents = str_replace("\r\n", "\n", $sContents); # harmonise first
        $sContents = str_replace("\n", "\r\n", $sContents);
        break;

    case 'lf': # CRLF to LF
        $sContents = str_replace("\r\n", "\n", $sContents);
        break;

    case 'bm': # clean ICON attribute in Firefox bookmarks file
        $sContents = preg_replace('/ICON="[^"]+"/', '', $sContents);
        break;

    default:
        die(PHP_EOL . ' Unknown parameter: ' . $sAction . DUB_EOL);
}


$iF = file_put_contents($sFilename, $sContents);


if ( ! $iF)
{
    die(PHP_EOL . " '$sFilename' could not be processed." . DUB_EOL);
}
else
{
    echo PHP_EOL . " '$sFilename' processed and saved." . DUB_EOL;
}
