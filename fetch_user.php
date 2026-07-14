<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require 'config.php';

$username = trim($_GET['username'] ?? $_POST['username'] ?? '');
if ($username === '') {
    echo json_encode(["error" => "Username is required"]); exit;
}

try {
    // Fetch base columns that ALWAYS exist
    $stmt = $conn->prepare("SELECT id, name, email, username, created_at FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["error" => "User not found"]); exit;
    }

    // Safely try phone & location — may not exist on older installs
    $user['phone']    = null;
    $user['location'] = null;
    try {
        $extra = $conn->prepare("SELECT phone, location FROM users WHERE username = ?");
        $extra->execute([$username]);
        $row = $extra->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $user['phone']    = $row['phone']    ?? null;
            $user['location'] = $row['location'] ?? null;
        }
    } catch (Exception $ignored) { /* columns don't exist yet — no crash */ }

    echo json_encode($user);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>