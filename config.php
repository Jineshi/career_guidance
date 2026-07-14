<?php
$host = '127.0.0.1'; 
$port = '3307'; // Check XAMPP Control Panel — MySQL is usually 3306 or 3307
$dbname = 'career_guidance';
$username = 'root';
$password = ''; // Leave empty for default XAMPP

// ✅ FIX: CORS headers removed from here — they belong in each API file, not the DB config
// ✅ FIX: Added $password as 4th argument to PDO (was missing — caused connection issues)
// ✅ FIX: Added default fetch mode so all queries return associative arrays

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed. Please try again later."]));
}
?>