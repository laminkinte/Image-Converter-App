<?php
// db/config.php
$host = 'localhost';
$dbname = 'image_converter_db';
$username = 'root'; // Use your DB username
$password = 'Yh3@Wp7#Vc9&Lm1!'; // Use your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
