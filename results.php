<?php
session_start();

// 🔐 Admin-only access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}

require_once "config/db.php";

// Fetch all members
$stmt = $pdo->query("
    SELECT id, name, reg_no, phone, voted, assigned_number
    FROM members
    ORDER BY assigned_number ASC
");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Voting Register | JITIHADA GROUP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>

    <!-- Hamburger -->
    <button id="sidebarToggle" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-blue-700 text-white rounded-lg shadow">
        ☰
    </button>

    <div class="flex min-h-screen">

        <!-- Admin Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-gray-900 to-blue-900 text-white p-6 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50 shadow-xl">

            <div class="flex flex-col items-center mb-8 border-b border-white/20 pb-6">
                <img src="images/jitihada.jpeg" alt="Jitihada Logo"
                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                <h2 class="mt-3 font-bold text-lg tracking-wide">JITIHADA GROUP</h2>
                <span class="text-xs uppercase tracking-widest text-blue-200 mt-1">Admin Panel</span>
            </div>

            <nav class="space-y-3 text-sm">
                <a href="dashboard.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📊 Admin Dashboard</a>
                <a href="registration.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📝 Register Member</a>
                <a href="vote.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">🗳 Vote</a>
                <a href="members.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">👥 Members</a>
                <a href="results.php" class="block px-4 py-2 bg-white text-blue-900 rounded-lg font-semibold">📋 Voting
                    Register</a>
                <a href="api/export_csv.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">⬇ Export CSV</a>
                <a href="logout.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg text-red-500 font-semibold">🚪
                    Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:p-10 md:ml-64">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Admin Voting Register</h1>
                    <p class="text-gray-500 text-sm">Admin view – voting status & lucky numbers</p>
                </div>
                <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-medium shadow">
                    ● Admin Access
                </span>
            </div>

            <div class="bg-white shadow-lg rounded-2xl p-6 overflow-x-auto">
                <h2 class="font-semibold mb-4 text-gray-700">Member Register</h2>

                <table class="table table-striped table-hover w-full">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>REG.NO</th>
                            <th>Voted</th>
                            <th>Position Assigned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $index => $m): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($m['name']) ?></td>
                            <td><?= htmlspecialchars($m['reg_no']) ?></td>

                            <!-- Voted -->
                            <td>
                                <?php if ((int)$m['voted'] === 1): ?>
                                <span class="badge bg-success">Yes</span>
                                <?php else: ?>
                                <span class="badge bg-danger">No</span>
                                <?php endif; ?>
                            </td>

                            <!-- Position -->
                            <td>
                                <?php if ((int)$m['voted'] === 1): ?>
                                <?= htmlspecialchars($m['assigned_number']) ?>
                                <?php else: ?>
                                Not assigned
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <footer class="text-center text-xs text-gray-500 mt-10">
                © <?= date("Y") ?> JITIHADA GROUP Voting System<br>
                Powered by <span class="font-semibold">Dantechdevs developers</span>
            </footer>

        </main>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
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

    document.querySelectorAll('#sidebar nav a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 768) closeSidebar();
        });
    });
    </script>

</body>

</html>