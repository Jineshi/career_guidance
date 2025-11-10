<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require 'config.php'; // connect to database

// Get username (sent via GET or POST or from JS localStorage)
$username = $_GET['username'] ?? $_POST['username'] ?? '';

if ($username === '') {
    echo json_encode(["error" => "Username is required"]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT name, email, username, created_at FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found"]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
