<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require 'config.php';

// Only logged-in users can save results
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Please log in to save results."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $user_id       = $_SESSION['user_id'];
    $category      = $input['category']      ?? '';
    $subcategory   = $input['subcategory']   ?? '';
    $suitability   = $input['suitability']   ?? ''; // 'highly', 'moderately', 'not'
    $score         = intval($input['score']  ?? 0);
    $top_career    = $input['top_career']    ?? '';
    $result_json   = json_encode($input['result_data'] ?? []);

    if (!$category || !$subcategory || !$suitability) {
        echo json_encode(["error" => "Missing required fields."]);
        exit;
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO test_results (user_id, category, subcategory, suitability, score, top_career, result_json, taken_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $category, $subcategory, $suitability, $score, $top_career, $result_json]);
        echo json_encode(["success" => true, "message" => "Result saved successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Could not save result. Try again."]);
    }
}
?>