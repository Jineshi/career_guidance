<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
require_once 'config.php';

$input       = json_decode(file_get_contents('php://input'), true);
$username    = trim($input['username']    ?? '');
$exam_name   = trim($input['exam_name']   ?? '');
$career_name = trim($input['career_name'] ?? '');
$exam_level  = trim($input['exam_level']  ?? '');
$eligibility = trim($input['eligibility'] ?? '');

if (!$username || !$exam_name) {
    echo json_encode(["success"=>false,"error"=>"Missing fields"]); exit;
}

try {
    $u = $conn->prepare("SELECT id FROM users WHERE username=?");
    $u->execute([$username]);
    $user = $u->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo json_encode(["success"=>false,"error"=>"User not found"]); exit; }

    // Prevent duplicate
    $dup = $conn->prepare("SELECT id FROM saved_exams WHERE user_id=? AND exam_name=?");
    $dup->execute([$user['id'], $exam_name]);
    if ($dup->fetch()) {
        echo json_encode(["success"=>false,"error"=>"Exam already saved"]); exit;
    }

    $stmt = $conn->prepare("INSERT INTO saved_exams (user_id, exam_name, career_name, exam_level, eligibility) VALUES (?,?,?,?,?)");
    $stmt->execute([$user['id'], $exam_name, $career_name, $exam_level, $eligibility]);
    echo json_encode(["success"=>true,"id"=>$conn->lastInsertId()]);
} catch(Exception $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
?>