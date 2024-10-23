<?php

function createImageFromFile($filePath) {
    $image = null;
    $fileInfo = pathinfo($filePath);
    $extension = strtolower($fileInfo['extension']);

    switch ($extension) {
        case 'jpeg':
        case 'jpg':
            $image = @imagecreatefromjpeg($filePath);
            break;
        case 'png':
            $image = @imagecreatefrompng($filePath);
            break;
        case 'gif':
            $image = @imagecreatefromgif($filePath);
            break;
        case 'webp':
            $image = @imagecreatefromwebp($filePath);
            break;
        default:
            return false; // Unsupported format
    }

    if ($image === false) {
        echo "Error: GD library functions are not available or the file is invalid.";
        return false; // Indicate failure
    }

    return $image; // Successfully created image resource
}

function convertImage($imageResource, $outputFilePath, $desiredFormat) {
    switch ($desiredFormat) {
        case 'jpeg':
        case 'jpg':
            return imagejpeg($imageResource, $outputFilePath);
        case 'png':
            return imagepng($imageResource, $outputFilePath);
        case 'gif':
            return imagegif($imageResource, $outputFilePath);
        case 'webp':
            return imagewebp($imageResource, $outputFilePath);
        default:
            return false; // Unsupported format
    }
}
?>
