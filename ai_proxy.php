<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// ══════════════════════════════════════════════════════════
//  🔑 API KEY — loaded from .env file (never hardcoded)
//  1. Create a file called  .env  in your project root
//  2. Add this line:  GEMINI_API_KEY=your_actual_key_here
//  3. .env is in .gitignore so it NEVER goes to GitHub
// ══════════════════════════════════════════════════════════
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            [$k, $v] = explode('=', $line, 2);
            $_ENV[trim($k)] = trim($v);
        }
    }
}

$GEMINI_API_KEY = $_ENV['GEMINI_API_KEY'] ?? '';

$MODELS = [
    'gemini-2.5-flash',
    'gemini-2.0-flash',
    'gemini-1.5-flash-001',
];

// Self-test: open ai_proxy.php?test=1 in browser
if (isset($_GET['test'])) {
    if (empty($GEMINI_API_KEY)) {
        echo json_encode(["status" => "❌ Key not set — create .env with GEMINI_API_KEY=your_key"]);
        exit;
    }
    foreach ($MODELS as $model) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($GEMINI_API_KEY);
        $payload = json_encode(["contents"=>[["parts"=>[["text"=>"Say OK"]]]]]);
        $ch = curl_init($url);
        curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$payload,CURLOPT_HTTPHEADER=>["Content-Type: application/json"],CURLOPT_TIMEOUT=>10]);
        $r = curl_exec($ch); $rc = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        $d = json_decode($r, true);
        if ($rc === 200 && isset($d['candidates'])) {
            echo json_encode(["status" => "✅ Working!", "model" => $model]); exit;
        }
    }
    echo json_encode(["status" => "❌ All models failed"]); exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "POST method required"]); exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['prompt'])) {
    http_response_code(400); echo json_encode(["error" => "Missing prompt"]); exit;
}

if (empty($GEMINI_API_KEY)) {
    http_response_code(400);
    echo json_encode(["error" => "API key not configured. Create a .env file with GEMINI_API_KEY=your_key"]); exit;
}

$prompt = $input['prompt'];
$lastError = '';

foreach ($MODELS as $model) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($GEMINI_API_KEY);
    $payload = json_encode([
        "contents" => [["parts" => [["text" => $prompt]]]],
        "generationConfig" => ["temperature" => 0.7, "maxOutputTokens" => 4096]
    ]);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_TIMEOUT => 90,
    ]);
    $response = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr   = curl_error($ch);
    curl_close($ch);

    if ($curlErr) continue;
    $data = json_decode($response, true);

    if ($httpCode === 200 && isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        echo json_encode(["content" => [["text" => $data['candidates'][0]['content']['parts'][0]['text']]]]); exit;
    }
    if ($httpCode === 429) {
        http_response_code(429);
        echo json_encode(["error" => "Rate limit hit. Please wait 1-2 minutes.", "model" => $model]); exit;
    }
    $lastError = ($data['error']['message'] ?? "HTTP $httpCode from $model");
}

http_response_code(400);
echo json_encode(["error" => "All models failed. Last error: $lastError"]);
?>