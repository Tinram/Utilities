<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">

        <title>Uploader</title>

        <style>
            html * {margin:0; padding:0;}
            body {margin:3em; font:0.9em/1.5 Sans,FreeSans,verdana,sans-serif; background:#fff; color:#000;}
            h1 {font-size:1em; margin-bottom:30px;}
            form#uploader {width:250px; margin-bottom:50px; padding:1px 0;}
            form#uploader div {clear:both; margin-bottom:3px; height:20px;}
            form#uploader label {float:left;}
            form#uploader input {border:1px solid #999; border-radius:5px;}
            form#uploader input#upfile {border:0;}
            form#uploader input#uploadbut {padding:4px; font-size:0.85em; margin-top:20px;}
            form#uploader input#uploadbut:hover {background:#000; color:#fff; border-radius:6px; cursor:pointer;}
            .error {color:#c00;}
            .success {color:#00c;}
        </style>

    </head>

    <body>

<?php

/**
    * Uploader.
    *
    * Simple HTTP file transfer on a local network to circumvent firewall, WinSCP, etc vagaries.
    *
    * Setup:
    *        sudo mkdir /var/www/html/uploader/
    *        sudo chown <you>:www-data /var/www/html/uploader/
    *        sudo chmod 770 /var/www/html/uploader/
    *        mv uploader.php index.php
    *        mv index.php /var/www/html/uploader/
    *
    *        (.ini: post_max_size, upload_max_filesize)
    *        ensure port 80 is open to local network on host server
    *
    * @author         Martin Latter
    * @copyright      Martin Latter 09/12/2017
    * @version        0.04
    * @license        GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
    * @link           https://github.com/Tinram/Utilities.git
*/


define('MAX_UPLOAD', 2000000);

?>

        <h1>Upload file to <em><?php $sHostname = exec('hostname'); echo (!empty($sHostname) ? $sHostname : 'this server'); ?></em></h1>

        <form id="uploader" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars(strip_tags($_SERVER['PHP_SELF']), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD; ?>">
            <div><input type="file" name="upfile" id="upfile"></div>
            <input type="hidden" name="upload_check" value="1">
            <input type="submit" value="upload" id="uploadbut">
        </form>

<?php

$bSubmitted = (isset($_POST['upload_check'])) ? true : false;


if ($bSubmitted)
{
    if (filenameCheck())
    {
        if (fileUpload())
        {
            echo '<p class="success">\'' . $_FILES['upfile']['name'] . '\' uploaded.</p>';
        }
    }
}


function filenameCheck()
{
    $sFileattName = $_FILES['upfile']['name'];
    $bFileFlag = false;

    $reBadName = '/[+\*\?:|\\\\\/"<>@;=#\{\}`%\^\$&]/';
    $bBadNameFlag = preg_match($reBadName, $sFileattName);

    if ($bBadNameFlag)
    {
        echo '<p class="error">That\'s a very bad filename.</p>';
        return false;
    }
    else {
        return true;
    }
}


function fileUpload()
{
    $sFilename = str_replace(['/', '..'], '', $_FILES['upfile']['name']);
    $sDestinationDir = '' . basename($sFilename);

    if ($_FILES['upfile']['size'] > MAX_UPLOAD)
    {
        echo '<p class="error">File exceeds filesize limit.</p>';
        return false;
    }
    else if (($_FILES['upfile']['error'] === UPLOAD_ERR_INI_SIZE) || ($_FILES['upfile']['error'] === UPLOAD_ERR_FORM_SIZE))
    {
        echo '<p class="error">File is too large.</p>';
        return false;
    }
    else if ($_FILES['upfile']['error'] === UPLOAD_ERR_PARTIAL)
    {
        echo '<p class="error">File upload was interrupted.</p>';
        return false;
    }
    else if ($_FILES['upfile']['error'] === UPLOAD_ERR_NO_FILE)
    {
        echo '<p class="error">No file was uploaded.</p>';
        return false;
    }
    else if ($_FILES['upfile']['error'] === UPLOAD_ERR_CANT_WRITE)
    {
        echo '<p class="error">Server cannot write to the tmp/ directory.</p>';
        return false;
    }
    else if ($_FILES['upfile']['error'] === UPLOAD_ERR_OK)
    {
        if ( ! move_uploaded_file($_FILES['upfile']['tmp_name'], $sDestinationDir))
        {
            echo '<p class="error">Could not the save file in the server directory.</p>';
            return false;
        }
        else
        {
            return true;
        }
    }
}

?>

    </body>

</html>
