<?php
require_once "config/db.php";

// WARNING: Run this **once** and remove it after updating passwords

$stmt = $pdo->query("SELECT id, password FROM admin_users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $current = $user['password'];

    // Skip if already hashed (optional, checks for typical hash length)
    if (strlen($current) !== 60) {
        $newHash = password_hash($current, PASSWORD_DEFAULT);

        $update = $pdo->prepare("UPDATE admin_users SET password=? WHERE id=?");
        $update->execute([$newHash, $user['id']]);

        echo "Updated password for user ID {$user['id']}<br>";
    } else {
        echo "User ID {$user['id']} already hashed, skipping.<br>";
    }
}

echo "✅ All passwords updated!";