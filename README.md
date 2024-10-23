# Image Converter App

**Image Converter App** is a simple PHP-based web application that allows users to upload images and convert them to various formats (JPEG, PNG, GIF, WebP). It includes user authentication (Sign Up / Log In) as a requirement to access the image upload and conversion functionality. The app features an aesthetic Bootstrap frontend design.

## Features
- **Image Upload**: Users can upload images in formats like JPEG, PNG, GIF, and WebP.
- **Image Conversion**: Convert images to different formats (JPEG, PNG, GIF, WebP).
- **User Authentication**: Mandatory user sign-up and log-in before accessing the image conversion tool.
- **Download Converted Images**: After conversion, users can download the newly formatted images.
- **Responsive Design**: Uses Bootstrap 4.5 for mobile-friendly and modern design.
  
## Technologies Used
- **PHP**: Backend server-side scripting.
- **MySQL**: Database for storing user information (can be adapted for other DBMS).
- **HTML & CSS**: Basic structure and styling of the web pages.
- **JavaScript & jQuery**: For modal pop-ups and client-side interactions.
- **Bootstrap 4.5**: Frontend framework for responsive and aesthetic design.

## Requirements
To run this project, you will need:
- **XAMPP/WAMP**: To run a local server with PHP and MySQL.
- **PHP GD Library**: For image manipulation.
- **MySQL Database**: For storing user credentials.
- **Composer**: Optional but recommended for managing dependencies.

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/ImageConverterApp.git
cd ImageConverterApp
```

### 2. Setup Database
- Create a MySQL database (e.g., `image_converter_db`).
- Import the `db/schema.sql` file into the database to set up the necessary tables.
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
```

- Update the database configuration in `db/config.php`:
```php
<?php
$host = 'localhost';
$dbname = 'image_converter_db';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
```

### 3. Ensure PHP GD Library is Enabled
Make sure the PHP GD Library is enabled in your `php.ini` file. Locate the following line in the file and remove the semicolon to enable it:
```ini
extension=gd
```

### 4. Run the App
- Place the project folder in your XAMPP/WAMP server's `htdocs` directory.
- Start your Apache and MySQL server using XAMPP/WAMP.
- Open your browser and go to `http://localhost/ImageConverterApp`.

## Usage

### Sign Up / Log In
1. **Sign Up**: Create a new account by providing an email and password.
2. **Log In**: After signing up, log in with your credentials.

### Image Upload and Conversion
1. Once logged in, select an image to upload (JPEG, PNG, GIF, WebP).
2. Choose the format you want to convert the image to.
3. Click "Upload and Convert". After conversion, a download link will be provided.

## Code Overview

### 1. `index.php`
Main page for user interaction, handles image uploads and shows the download links for converted images. Also includes sign-up and login modals.

### 2. `image_functions.php`
Contains the functions to handle image conversion using the PHP GD library:
- `createImageFromFile()`: Creates an image resource from an uploaded file based on its format.
- `convertImage()`: Converts an image resource to the desired format.

### 3. `db/config.php`
Configuration file for connecting to the MySQL database.

### 4. `assets/images/`
Directory where uploaded and converted images are stored.

## Example Code

### Image Upload and Conversion in `index.php`:
```php
if (isset($_POST['upload'])) {
    $image = $_FILES['image'];
    $target_file = $target_dir . basename($image['name']);
    
    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        $imageResource = createImageFromFile($target_file);
        if ($imageResource !== false) {
            $converted_file = $target_dir . pathinfo($original_filename, PATHINFO_FILENAME) . '.' . $desired_format;
            convertImage($imageResource, $converted_file, $desired_format);
            imagedestroy($imageResource);
            $download_links[] = $converted_file;
        }
    }
}
```

### Image Conversion in `image_functions.php`:
```php
function createImageFromFile($filename) {
    $image_info = getimagesize($filename);
    switch ($image_info[2]) {
        case IMAGETYPE_JPEG:
            return imagecreatefromjpeg($filename);
        case IMAGETYPE_PNG:
            return imagecreatefrompng($filename);
        case IMAGETYPE_GIF:
            return imagecreatefromgif($filename);
        case IMAGETYPE_WEBP:
            return imagecreatefromwebp($filename);
        default:
            return false;
    }
}

function convertImage($imageResource, $output_file, $format) {
    switch ($format) {
        case 'jpeg':
            return imagejpeg($imageResource, $output_file);
        case 'png':
            return imagepng($imageResource, $output_file);
        case 'gif':
            return imagegif($imageResource, $output_file);
        case 'webp':
            return imagewebp($imageResource, $output_file);
        default:
            return false;
    }
}
```

## Screenshots

### Image Upload Page:
![image](assets/screenshots/upload_page.png)

### Sign Up Modal:
![image](assets/screenshots/signup_modal.png)

## Future Enhancements
- **Image compression**: Adding an option to compress the images during conversion.
- **Email verification**: Enhancing the sign-up process with email verification.
- **History feature**: Allow users to view and download their previously converted images.

