<?php
require_once "config/db.php";

$message = "";
$alertType = "";

/* =========================
   CHECK REMAINING VOTING SLOTS
========================= */
$stmt = $pdo->query("SELECT COUNT(*) FROM members WHERE voted = 1");
$usedSlots = (int)$stmt->fetchColumn();
$remainingSlots = max(0, 10 - $usedSlots);
$votingClosed = ($remainingSlots <= 0);
/* =========================
   HANDLE VOTING
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$votingClosed) {
    $reg_no = strtoupper(trim($_POST['reg_no']));

    // Initialize variables to avoid undefined warnings
    $assigned_number = null;
    $member = null;

    // Fetch member
    $stmt = $pdo->prepare("SELECT id, name, voted, assigned_number FROM members WHERE reg_no = ?");
    $stmt->execute([$reg_no]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        $message = "❌ You must be registered to vote.";
        $alertType = "danger";
    } elseif ($member['voted']) {
        $message = "⚠ You already voted. Your number was: {$member['assigned_number']}";
        $alertType = "warning";
    } else {
        try {
            $pdo->beginTransaction();

            // Lock used numbers to prevent duplicates
            $stmt = $pdo->query("SELECT assigned_number FROM members WHERE assigned_number IS NOT NULL FOR UPDATE");
            $usedNumbers = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $allowed = range(1, 10);
            $available = array_values(array_diff($allowed, $usedNumbers));

            if (empty($available)) {
                throw new Exception("No numbers left");
            }

            // Assign a random available number
            $assigned_number = $available[array_rand($available)];

            // Update member vote and record timestamp
            $stmt = $pdo->prepare("
                UPDATE members 
                SET assigned_number = ?, voted = 1, voted_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$assigned_number, $member['id']]);

            $pdo->commit();

            $message = "🎉 Vote successful! Your lucky number is: $assigned_number";
            $alertType = "success";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "🚫 Voting Closed — All 10 slots are filled.";
            $alertType = "danger";
        }

        // Recalculate remaining slots after voting
        $stmt = $pdo->query("SELECT COUNT(*) FROM members WHERE voted = 1");
        $usedSlots = (int)$stmt->fetchColumn();
        $remainingSlots = max(0, 10 - $usedSlots);
        $votingClosed = ($remainingSlots <= 0);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Vote | JITIHADA GROUP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fade .5s ease-in-out;
        }

        .marquee {
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 15s linear infinite;
        }

        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>

    <!-- Hamburger Button -->
    <button id="sidebarToggle" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-blue-700 text-white rounded-lg shadow">
        ☰
    </button>

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-900 to-indigo-900 text-white p-6 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">

            <div class="flex flex-col items-center mb-8">
                <img src="images/jitihada.jpeg" class="w-24 h-24 rounded-full border-4 border-white shadow">
                <h2 class="mt-3 font-bold">JITIHADA GROUP</h2>
            </div>

            <nav class="space-y-3 text-sm">
                <a href="dashboard.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📊 Dashboard</a>
                <a href="registration.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📝 Register Member</a>
                <a href="vote.php" class="block px-4 py-2 bg-white text-blue-900 rounded-lg font-semibold">🗳 Vote</a>
                <a href="members.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">👥 Members</a>
                <a href="results.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📈 Results</a>
                <a href="api/export_csv.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">⬇ Export CSV</a>
            </nav>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 flex flex-col items-center justify-center p-4 md:ml-64">

            <!-- Moving Message -->
            <div class="marquee mb-4 text-blue-700 font-semibold">
                <span>✨ You are a Valued Member at Jitihada Group ✨</span>
            </div>

            <!-- CARD -->
            <div class="bg-white shadow-xl rounded-2xl p-6 w-full max-w-md fade-in text-center">
                <h1 class="text-2xl font-bold mb-1">🗳 Voting</h1>
                <p class="text-gray-500 mb-3">Enter your REG.NO to get your lucky number (1–10)</p>

                <span class="inline-block bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm mb-4">
                    ● System Active
                </span>

                <?php if ($message): ?>
                    <div class="alert alert-<?= $alertType ?>"><?= $message ?></div>
                <?php endif; ?>

                <?php if ($votingClosed): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg">
                        🚫 Voting Closed — All 10 slots are filled.
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <span id="slotsRemaining" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                            📊 Slots Remaining: <?= $remainingSlots ?>
                        </span>
                    </div>

                    <form method="POST" class="space-y-3">
                        <input type="text" name="reg_no" required placeholder="Enter REG.NO (e.g. JG0001)"
                            class="form-control text-center">
                        <button class="btn btn-success w-full">🗳 Vote</button>
                    </form>
                <?php endif; ?>

                <a href="dashboard.php" class="mt-4 inline-block text-blue-600 hover:underline">
                    ← Back to Dashboard
                </a>
            </div>

        </main>
    </div>

    <!-- LIVE UPDATE & SIDEBAR TOGGLE -->
    <script>
        async function updateSlots() {
            const res = await fetch("api/remaining_slots.php");
            const data = await res.json();
            const badge = document.getElementById("slotsRemaining");
            if (badge) badge.innerText = "📊 Slots Remaining: " + data.remaining;
            if (data.remaining <= 0) location.reload();
        }
        setInterval(updateSlots, 5000);

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