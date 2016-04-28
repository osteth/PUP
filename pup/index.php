<?php
header('Content-Type: text/plain; charset=utf-8');
$maxFileSize = 10000000;

$allowedFileExtensions = array
(
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
);

$allowAllExtensions = true;
// if path is changed then the substring lenght will have to be modified to get proper addresses to uploads
$uploadPath = "/var/www/html/pup/uploads/";

try
{

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (!isset($_FILES['uploaded-file']['error']) || is_array($_FILES['uploaded-file']['error']))
    {
        var_dump($_FILES);
var_dump($_REQUEST);

        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['uploaded-file']['error'] value.
    switch ($_FILES['uploaded-file']['error'])
    {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('Blank or no file sent');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('File size limit exceeded');
        default:
            throw new RuntimeException('Something');
    }

    // Don't rely on PHP config to correctly enforce file sizes - we all know php's reputation
    if ($_FILES['uploaded-file']['size'] > $maxFileSize)
    {
        throw new RuntimeException('File size limit exceeded');
    }

    // DO NOT TRUST $_FILES['uploaded-file']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $extension = array_search( $finfo->file($_FILES['uploaded-file']['tmp_name']),$allowedFileExtensions, true );
 //    if (false === $extension || $allowAllExtensions == false)
   // {
     //   throw new RuntimeException('File extension not allowed');
    //}

    // You should name it uniquely.
    $fullPath = $uploadPath . $_SERVER['REMOTE_ADDR'] . "/";
    $fileName = $_FILES['uploaded-file']['name'];
    preg_replace("/..\//", "", $fileName);

    umask(0666);
    mkdir($fullPath, 0666, true);
    chmod($fullPath, 0766);
    $date = date_create();
    $newFileName = $fullPath . date_format($date, 'Y-m-d_H-i-s')  . $fileName;
    $fileMoveWorked = move_uploaded_file($_FILES['uploaded-file']['tmp_name'], $newFileName);

    if ($fileMoveWorked == false)
    {
        throw new RuntimeException("Couldn't save the file\n");
    }
//This sets the output returned to the user when a file is saved. It should be set to give a link to the uploaded file.
        echo " File saved to: Enter Server Domain Name Here" .substr($newFileName, 10) . "\n";
} catch (RuntimeException $e) {

    echo "Exception thrown: " . $e->getMessage();

}

?>
