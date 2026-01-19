<?php
session_start();
require_once "config/db.php";

$message = "";

if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch user by username
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username=?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        $_SESSION['admin_user'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];
        header("Location: results.php");
        exit;
    } else {
        $message = "❌ Login failed. Check your username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | JITIHADA GROUP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(to right, #1e3a8a, #4f46e5);
    }

    .card {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="card bg-white rounded-3xl shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-4 text-blue-900">🔐 Admin Login</h2>
        <?php if ($message): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <input name="username" placeholder="Username" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="relative">
                <input id="password" name="password" type="password" placeholder="Password" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" id="togglePassword"
                    class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-500 hover:text-gray-800">👁️</button>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                Login
            </button>
        </form>
        <p class="mt-4 text-center text-gray-500 text-sm">Powered by <strong>Dantechdevs</strong></p>
    </div>
    <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    togglePassword.addEventListener('click', () => {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
    });
    </script>
</body>

</html>