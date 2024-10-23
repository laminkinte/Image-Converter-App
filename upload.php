<?php
require 'config.php';
require 'image_functions.php'; // Include the helper functions for image processing

if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $target_dir = __DIR__ . "/assets/images/";
    $original_filename = basename($image['name']);
    $target_file = $target_dir . $original_filename;

    // Ensure the directory exists, or create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Check if the file was uploaded without errors
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Try to move the uploaded file to the target directory
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            echo "File successfully uploaded: " . $original_filename;

            // Get the desired format from the user input
            $desired_format = $_POST['format'];
            $converted_file = $target_dir . pathinfo($original_filename, PATHINFO_FILENAME) . '.' . $desired_format;

            // Create an image resource from the uploaded file
            $imageResource = createImageFromFile($target_file);
            
            if ($imageResource === false) {
                echo "Unsupported file format. Only JPEG, PNG, GIF, and WebP are allowed.";
                exit;
            }

            // Convert the image to the desired format
            if (convertImage($imageResource, $converted_file, $desired_format)) {
                // Free up memory
                imagedestroy($imageResource);

                echo "Image converted to $desired_format successfully.";
                
                // Redirect to download the file
                header("Location: download.php?file=" . urlencode($converted_file));
                exit;
            } else {
                echo "Failed to convert the image.";
            }
        } else {
            echo "Sorry, there was an error moving the uploaded file.";
        }
    } else {
        echo "Upload failed with error code: " . $image['error'];
    }
}
?>
