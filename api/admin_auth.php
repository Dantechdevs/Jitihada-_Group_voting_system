<?php
session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: ../admin_login.php?error=Please+fill+all+fields");
        exit;
    }

    // Fetch admin from DB
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if ($admin && password_verify($password, $admin['password'])) {
        // Regenerate session ID for security
        session_regenerate_id(true);

        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $admin['username'];

        header("Location: ../results.php");
        exit;
    } else {
        // Redirect with error
        header("Location: ../admin_login.php?error=Invalid+credentials");
        exit;
    }
} else {
    // Block direct access
    header("Location: ../admin_login.php");
    exit;
}
