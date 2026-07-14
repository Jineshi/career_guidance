<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
require_once 'config.php';

$username = trim($_GET['username'] ?? '');
if (!$username) { echo json_encode(["error"=>"Username required"]); exit; }

try {
    $u = $conn->prepare("SELECT id FROM users WHERE username=?");
    $u->execute([$username]);
    $user = $u->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo json_encode(["error"=>"User not found"]); exit; }

    $stmt = $conn->prepare("SELECT id, exam_name, career_name, exam_level, eligibility, saved_at FROM saved_exams WHERE user_id=? ORDER BY saved_at DESC");
    $stmt->execute([$user['id']]);
    echo json_encode(["success"=>true,"exams"=>$stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch(Exception $e) {
    echo json_encode(["error"=>$e->getMessage()]);
}
?>