<?php
require_once "../config/db.php";

// Admin credentials
$username = "admin";          // change username
$password_plain = "Secret123"; // change password

// Check if admin already exists
$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    echo "Admin with username '{$username}' already exists!";
} else {
    // Hash the password
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    // Insert new admin
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password_hashed]);

    echo "Admin '{$username}' added successfully!";
}
