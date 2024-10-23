<?php 
require 'config.php';
require 'image_functions.php';

session_start();

$upload_message = '';
$download_links = [];
$show_upload_form = false; // Controls whether to show upload form

// Check if user is logged in
if (isset($_SESSION['user_email'])) {
    $show_upload_form = true;
}

// Handle image upload
if (isset($_POST['upload']) && $show_upload_form) {
    $image = $_FILES['image'];
    $target_dir = __DIR__ . "/assets/images/";
    $original_filename = basename($image['name']);
    $target_file = $target_dir . $original_filename;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if ($image['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $upload_message = "File successfully uploaded: " . $original_filename;
            $desired_format = $_POST['format'];
            $converted_file = $target_dir . pathinfo($original_filename, PATHINFO_FILENAME) . '.' . $desired_format;
            $imageResource = createImageFromFile($target_file);

            if ($imageResource === false) {
                $upload_message = "Unsupported file format. Only JPEG, PNG, GIF, and WebP are allowed.";
            } else {
                if (convertImage($imageResource, $converted_file, $desired_format)) {
                    imagedestroy($imageResource);
                    $download_links[] = $converted_file;
                } else {
                    $upload_message = "Failed to convert the image.";
                }
            }
        } else {
            $upload_message = "Sorry, there was an error moving the uploaded file.";
        }
    } else {
        $upload_message = "Upload failed with error code: " . $image['error'];
    }
}

// Handle signup
if (isset($_POST['signup'])) {
    $email = $_POST['signupEmail'];
    $password = password_hash($_POST['signupPassword'], PASSWORD_DEFAULT);

    // You can store this information in your database and create user accounts.
    $_SESSION['user_email'] = $email;
    $show_upload_form = true;
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    // Assuming that user authentication is successful
    $_SESSION['user_email'] = $email;
    $show_upload_form = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Converter App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            margin: 20px 0;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Image Converter App</h1>

    <?php if (!$show_upload_form): ?>
        <div class="alert alert-info text-center">
            Please sign up or log in to use the image conversion tool.
        </div>
    <?php endif; ?>

    <?php if ($show_upload_form): ?>
        <div class="card">
            <div class="card-header">Upload Image</div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image">Choose Image</label>
                        <input type="file" class="form-control-file" name="image" required>
                    </div>
                    <div class="form-group">
                        <label for="format">Convert to:</label>
                        <select name="format" class="form-control" required>
                            <option value="jpeg">JPEG</option>
                            <option value="png">PNG</option>
                            <option value="gif">GIF</option>
                            <option value="webp">WebP</option>
                        </select>
                    </div>
                    <button type="submit" name="upload" class="btn btn-primary">Upload and Convert</button>
                </form>
                <div class="mt-3 text-success"><?php echo $upload_message; ?></div>
            </div>
        </div>

        <?php if (!empty($download_links)): ?>
            <div class="card">
                <div class="card-header">Download Links</div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($download_links as $download_link): ?>
                            <li class="list-group-item">
                                <a href="<?php echo 'assets/images/' . basename($download_link); ?>" class="btn btn-success" download>Download <?php echo basename($download_link); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">User Authentication</div>
        <div class="card-body">
            <button class="btn btn-secondary" data-toggle="modal" data-target="#signupModal">Sign Up</button>
            <button class="btn btn-secondary" data-toggle="modal" data-target="#loginModal">Log In</button>
        </div>
    </div>
</div>

<!-- Sign Up Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signupModalLabel">Sign Up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="signupEmail">Email address</label>
                        <input type="email" class="form-control" id="signupEmail" name="signupEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="signupPassword">Password</label>
                        <input type="password" class="form-control" id="signupPassword" name="signupPassword" required>
                    </div>
                    <button type="submit" name="signup" class="btn btn-primary">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Log In</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="loginEmail">Email address</label>
                        <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Log In</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
