<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
require_once 'config.php'; // provides $conn

$input     = json_decode(file_get_contents('php://input'), true);
$username  = trim($input['username']         ?? '');
$currentPw = $input['current_password']      ?? '';
$newPw     = $input['new_password']          ?? '';

if (!$username || !$currentPw || !$newPw) {
    echo json_encode(["success"=>false,"error"=>"Missing fields"]); exit;
}
if (strlen($newPw) < 6) {
    echo json_encode(["success"=>false,"error"=>"New password must be at least 6 characters"]); exit;
}

try {
    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($currentPw, $user['password'])) {
        echo json_encode(["success"=>false,"error"=>"Current password is incorrect"]); exit;
    }

    $hashed = password_hash($newPw, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password=? WHERE username=?");
    $update->execute([$hashed, $username]);
    echo json_encode(["success"=>true]);
} catch(Exception $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
?>