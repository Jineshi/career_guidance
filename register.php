<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $username, $email, $password]);
        echo "success"; // 👈 plain text, no HTML
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "duplicate"; // duplicate username/email
        } else {
            echo "error";
        }
    }
}
?>
