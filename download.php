<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);

    if (file_exists($file)) {
        // Set headers to trigger download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        // Clear output buffer and read the file for download
        ob_clean();
        flush();
        readfile($file);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
?>
