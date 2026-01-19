<?php
require_once "config/db.php";
$data = $pdo->query("SELECT * FROM members ORDER BY assigned_number ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Members | JITIHADA GROUP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- Sidebar (identical to dashboard) -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-indigo-900 text-white p-6 hidden md:block">

            <div class="flex flex-col items-center mb-8">
                <img src="images/jitihada.jpeg" alt="Jitihada Logo"
                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                <h2 class="mt-3 font-bold text-lg tracking-wide">JITIHADA GROUP</h2>
            </div>

            <nav class="space-y-3 text-sm">
                <a href="dashboard.php"
                    class="block px-4 py-2 hover:bg-white hover:text-blue-900 rounded-lg font-semibold">📊 Dashboard</a>
                <a href="registration.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📝 Register Member</a>
                <a href="vote.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">🗳 Vote</a>
                <a href="members.php" class="block px-4 py-2 bg-white text-blue-900 rounded-lg font-semibold">👥
                    Members</a>
                <a href="results.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📈 Results</a>
                <a href="api/export_csv.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">⬇ Export CSV</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:p-8">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Members List</h1>
                <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-medium shadow">
                    ● System Active
                </span>
            </div>

            <!-- Search -->
            <input type="text" id="search" placeholder="Search by Name, REG.NO or Phone"
                class="w-full mb-4 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Members Table -->
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-3 text-left">REG.NO</th>
                            <th class="p-3 text-left">Full Name</th>
                            <th class="p-3 text-left">Phone</th>
                            <th class="p-3 text-left">Assigned Number</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody id="members-table">
                        <?php foreach ($data as $m): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3"><?= $m['reg_no'] ?></td>
                            <td class="p-3"><?= $m['full_name'] ?></td>
                            <td class="p-3"><?= $m['phone'] ?></td>
                            <td class="p-3"><?= $m['assigned_number'] ?></td>
                            <td class="p-3">
                                <?php if ($m['has_voted']): ?>
                                <span class="px-2 py-1 rounded-full text-white bg-green-500">Voted</span>
                                <?php else: ?>
                                <span class="px-2 py-1 rounded-full text-white bg-red-500">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <script>
    // Live Search
    const searchInput = document.getElementById('search');
    const tableRows = document.querySelectorAll('#members-table tr');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
    </script>

</body>

</html>