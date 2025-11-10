<?php
$host = '127.0.0.1'; 
$port = '3307'; // check in XAMPP control panel if MySQL runs on this
$dbname = 'career_guidance';
$username = 'root';
$password = '';
header("Access-Control-Allow-Origin: *"); // allow all origins (for development)
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
