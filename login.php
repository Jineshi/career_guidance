<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require 'config.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo 'Please fill in all fields.';
        exit;
    }

    try {
        // Prepare SQL to fetch user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Compare hashed password
            if (password_verify($password, $user['password'])) {
                echo 'success';
            } else {
                echo 'Invalid password!';
            }
        } else {
            echo 'No such user found!';
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?>
