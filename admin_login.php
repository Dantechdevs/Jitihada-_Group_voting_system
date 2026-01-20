<?php
session_start();

// Redirect if already logged in as admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: results.php'); // or dashboard.php
    exit;
}

// Optional: show error if login failed
$error = '';
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | JITIHADA GROUP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS for buttons if desired -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-6">
            <img src="images/jitihada.jpeg" alt="Jitihada Logo"
                class="w-20 h-20 rounded-full mb-3 border-2 border-blue-600">
            <h2 class="text-2xl font-bold text-gray-800">Admin Login</h2>
            <p class="text-gray-500 text-sm">Enter your credentials to continue</p>
        </div>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="api/admin_auth.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                Login
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            © <?= date('Y') ?> JITIHADA GROUP Voting System
        </p>

    </div>

</body>

</html>