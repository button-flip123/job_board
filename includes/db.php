<?php
$host = 'localhost';
$db   = 'jobboard';
$user = 'root';        // default u XAMPP-u
$pass = '';            // default prazan

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Greška u konekciji: " . $e->getMessage());
}
?>