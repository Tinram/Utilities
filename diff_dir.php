#!/usr/bin/env php
<?php

/**
    * Diff dir differences - either filenames or file content hashes.
    *
    * Meld provides a nice GUI dir diff, yet I needed something quick and simple in the terminal.
    *
    * Usage:
    *        php diff_dir <filename|filehash> <dir1> <dir2>
    *
    * @author         Martin Latter
    * @copyright      Martin Latter 26/12/2019
    * @version        0.01
    * @license        GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
    * @link           https://github.com/Tinram/Utilities.git
*/

define('DUB_EOL', PHP_EOL . PHP_EOL);

$sUsage =
    PHP_EOL . ' ' . basename(__FILE__, '.php') .
    DUB_EOL . "\tusage: php " . basename(__FILE__) . ' <filename|filehash> <dir1> <dir2>' . DUB_EOL;

if ( ! isset($_SERVER['argv'][1]) || ($_SERVER['argv'][1] !== 'filename' && $_SERVER['argv'][1] !== 'filehash'))
{
    echo PHP_EOL . ' parameter 1 must be \'filename\' or \'filehash\'' . PHP_EOL;
    die($sUsage);
}

if ( ! isset($_SERVER['argv'][2]))
{
    echo PHP_EOL . ' missing <dir1>' . PHP_EOL;
    die($sUsage);
}

if ( ! isset($_SERVER['argv'][3]))
{
    echo PHP_EOL . ' missing <dir2>' . PHP_EOL;
    die($sUsage);
}

$sSearch = $_SERVER['argv'][1];
$sDir1 = $_SERVER['argv'][2];
$sDir2 = $_SERVER['argv'][3];
$aFileList1 = [];
$aFileList2 = [];

if ( ! is_dir($sDir1))
{
    echo PHP_EOL . '<dir1> is not a directory!' . PHP_EOL;
    die($sUsage);
}

if ( ! is_dir($sDir2))
{
    echo PHP_EOL . '<dir2> is not a directory!' . PHP_EOL;
    die($sUsage);
}

$oDirIterator1 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sDir1, FilesystemIterator::SKIP_DOTS));
$oDirIterator2 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sDir2, FilesystemIterator::SKIP_DOTS));

foreach ($oDirIterator1 as $oFileDetails)
{
    $sFilename = $oFileDetails->getFilename();
    $sPath = $oFileDetails->getPath();

    if (strpos($sPath, 'git') !== false) {continue;} # ignore git-related files

    if ($sSearch === 'filename')
    {
        $aFileList1[hash('md5', $sFilename)] = $sPath . DIRECTORY_SEPARATOR . $sFilename;
    }
    else if ($sSearch === 'filehash')
    {
        $aFileList1[hash_file('md5', $sPath . DIRECTORY_SEPARATOR . $sFilename)] = $sPath . DIRECTORY_SEPARATOR . $sFilename;
    }
}

foreach ($oDirIterator2 as $oFileDetails2)
{
    $sFilename = $oFileDetails2->getFilename();
    $sPath = $oFileDetails2->getPath();

    if (strpos($sPath, 'git') !== false) {continue;}

    if ($sSearch === 'filename')
    {
        $aFileList2[hash('md5', $sFilename)] = $sPath . DIRECTORY_SEPARATOR . $sFilename;
    }
    else if ($sSearch === 'filehash')
    {
        $aFileList2[hash_file('md5', $sPath . DIRECTORY_SEPARATOR . $sFilename)] = $sPath . DIRECTORY_SEPARATOR . $sFilename;
    }
}

$aDiff = array_diff_key($aFileList2, $aFileList1); # array1 to have the differences

if (empty($aDiff))
{
    $aDiff = array_diff_key($aFileList1, $aFileList2); # reverse for array_diff_key()
}

echo 'file differences (' . $sSearch . '):' . PHP_EOL;

foreach ($aDiff as $f)
{
    echo $f . PHP_EOL;
}
