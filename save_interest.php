<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
require_once 'config.php';

$input      = json_decode(file_get_contents('php://input'), true);
$username   = trim($input['username']    ?? '');
$careerName = trim($input['career_name'] ?? '');
$action     = trim($input['action']      ?? 'save'); // save | delete

if (!$username || !$careerName) {
    echo json_encode(["success"=>false,"error"=>"Missing username or career_name"]); exit;
}

try {
    $u = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $u->execute([$username]);
    $user = $u->fetch(PDO::FETCH_ASSOC);
    if (!$user) { echo json_encode(["success"=>false,"error"=>"User not found"]); exit; }

    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM career_interests WHERE user_id = ? AND career_name = ?");
        $stmt->execute([$user['id'], $careerName]);
        echo json_encode(["success"=>true,"message"=>"Removed from interests"]);
    } else {
        // INSERT IGNORE handles duplicates
        $stmt = $conn->prepare("INSERT IGNORE INTO career_interests (user_id, career_name) VALUES (?, ?)");
        $stmt->execute([$user['id'], $careerName]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success"=>true,"message"=>"Saved to interests"]);
        } else {
            echo json_encode(["success"=>false,"already"=>true,"message"=>"Already in interests"]);
        }
    }
} catch(Exception $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
?>