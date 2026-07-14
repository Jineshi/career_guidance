<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
require_once 'config.php';

$input    = json_decode(file_get_contents('php://input'), true);
$username = trim($input['username'] ?? '');
$name     = trim($input['name']     ?? '');
$email    = trim($input['email']    ?? '');
$phone    = trim($input['phone']    ?? '');
$location = trim($input['location'] ?? '');

if (!$username || !$name || !$email) {
    echo json_encode(["success"=>false,"error"=>"Name and email required"]); exit;
}

try {
    // Check email not used by another account
    $check = $conn->prepare("SELECT id FROM users WHERE email=? AND username!=?");
    $check->execute([$email, $username]);
    if ($check->fetch()) {
        echo json_encode(["success"=>false,"error"=>"Email already used by another account"]); exit;
    }

    // Always update name + email (always exist)
    $conn->prepare("UPDATE users SET name=?, email=? WHERE username=?")
         ->execute([$name, $email, $username]);

    // Try to update phone + location only if columns exist
    try {
        $conn->prepare("UPDATE users SET phone=?, location=? WHERE username=?")
             ->execute([$phone, $location, $username]);
    } catch (Exception $ignored) {
        // Columns don't exist yet — run safe_migrate.sql in phpMyAdmin to add them
    }

    echo json_encode(["success"=>true]);
} catch(Exception $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
?>