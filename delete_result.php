<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
require_once 'config.php';

$input    = json_decode(file_get_contents('php://input'), true);
$id       = intval($input['id']       ?? 0);
$username = trim($input['username']   ?? '');

if (!$id || !$username) { echo json_encode(["success"=>false,"error"=>"Invalid request"]); exit; }

try {
    $u = $conn->prepare("SELECT id FROM users WHERE username=?");
    $u->execute([$username]);
    $user = $u->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo json_encode(["success"=>false,"error"=>"User not found"]); exit; }

    $stmt = $conn->prepare("DELETE FROM test_results WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user['id']]);
    echo json_encode(["success"=>true]);
} catch(Exception $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
?>