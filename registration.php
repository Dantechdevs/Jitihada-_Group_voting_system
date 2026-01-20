<?php
require_once "config/db.php";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    // Validate name and phone
    if (empty($name)) {
        $message = "Name is required";
    } elseif (!preg_match("/^\+?\d{10,15}$/", $phone)) {
        $message = "Phone number is invalid";
    } else {
        try {
            // Prevent duplicate phone
            $stmt = $pdo->prepare("SELECT * FROM members WHERE phone = ?");
            $stmt->execute([$phone]);

            if ($stmt->rowCount()) {
                $message = "Member with this phone already exists!";
            } else {
                // Check total registered members
                $stmt = $pdo->query("SELECT COUNT(*) AS total FROM members");
                $total = (int)$stmt->fetchColumn();

                if ($total >= 10) {
                    $message = "Registration full. Only 10 members allowed.";
                } else {
                    // Generate reg_no automatically (JG0001, JG0002...)
                    $reg_no = "JG" . str_pad($total + 1, 4, "0", STR_PAD_LEFT);

                    $insert = $pdo->prepare("INSERT INTO members (reg_no, name, phone, voted, assigned_number) VALUES (?, ?, ?, 0, NULL)");
                    $insert->execute([$reg_no, $name, $phone]);

                    $message = "<span class='text-green-700 font-bold'>✅ Registered successfully!</span><br>
                                REG.NO: <strong>$reg_no</strong><br>
                                Note: Voting numbers (1-10) will be assigned later.";
                }
            }
        } catch (PDOException $e) {
            $message = "<span class='text-red-700 font-bold'>Error: " . $e->getMessage() . "</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Member | JITIHADA GROUP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        background: #f3f4f6;
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

<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-900 to-indigo-900 text-white p-6 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">
        <div class="flex flex-col items-center mb-8">
            <img src="images/jitihada.jpeg" alt="Jitihada Logo"
                class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
            <h2 class="mt-3 font-bold text-lg tracking-wide">JITIHADA GROUP</h2>
        </div>

        <nav class="space-y-3 text-sm">
            <a href="dashboard.php"
                class="block px-4 py-2 hover:bg-white hover:text-blue-900 rounded-lg font-semibold">📊 Dashboard</a>
            <a href="registration.php" class="block px-4 py-2 bg-white text-blue-900 rounded-lg font-semibold">📝
                Register Member</a>
            <a href="vote.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">🗳 Vote</a>
            <a href="members.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">👥 Members</a>
            <a href="results.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📈 Results</a>
            <a href="api/export_csv.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">⬇ Export CSV</a>
        </nav>
    </aside>

    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>

    <!-- Hamburger Button -->
    <button id="sidebarToggle" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-blue-700 text-white rounded-lg shadow">
        ☰
    </button>

    <!-- Main -->
    <main class="flex-1 flex justify-center items-center p-6 md:p-8">
        <div class="card bg-white rounded-3xl shadow-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4 text-center text-blue-900">📝 Member Registration</h2>

            <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <input type="text" name="name" placeholder="Full Name" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="phone" placeholder="Phone Number (e.g. +2547XXXXXXXX)" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                    Register
                </button>
            </form>

            <a href="dashboard.php" class="mt-4 block text-center text-blue-700 hover:underline font-semibold">← Back to
                Dashboard</a>
        </div>
    </main>

    <script>
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggle = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    }

    sidebarToggle.addEventListener('click', openSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar on mobile link click
    document.querySelectorAll('#sidebar nav a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 768) closeSidebar();
        });
    });
    </script>
</body>

</html>