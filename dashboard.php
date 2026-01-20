<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>JITIHADA GROUP | Voting Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        darkMode: 'class'
    }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    /* Sidebar sliding styles */
    #sidebar {
        transition: transform 0.3s ease;
        /* Slide off screen initially on mobile */
        transform: translateX(-100%);
        overflow-y: auto;
        /* Enable scrolling if content is tall */
    }

    /* Show sidebar on md+ screens */
    @media (min-width: 768px) {
        #sidebar {
            transform: translateX(0) !important;
            position: relative !important;
        }
    }

    /* When active class is toggled, slide sidebar in */
    #sidebar.active {
        transform: translateX(0);
        position: fixed !important;
    }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 bg-gradient-to-b from-blue-900 to-indigo-900 text-white p-6 fixed md:relative h-full z-50">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-8">
                <img src="images/jitihada.jpeg" alt="Jitihada Logo"
                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg" />
                <h2 class="mt-3 font-bold text-lg tracking-wide">JITIHADA GROUP</h2>
            </div>

            <!-- Menu -->
            <nav class="space-y-3 text-sm">
                <a href="dashboard.php" class="block px-4 py-2 bg-white text-blue-900 rounded-lg font-semibold">📊
                    Dashboard</a>
                <a href="registration.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📝 Register Member</a>
                <a href="vote.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">🗳 Vote</a>
                <a href="members.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">👥 Members</a>
                <a href="results.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">📈 Results</a>
                <a href="api/export_csv.php" class="block px-4 py-2 hover:bg-blue-700 rounded-lg">⬇ Export CSV</a>
            </nav>

            <!-- Dark Mode Toggle -->
            <button id="darkToggle"
                class="mt-8 px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded w-full">
                Toggle Dark Mode
            </button>
        </aside>

        <!-- Sidebar overlay (mobile only) -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>

        <!-- Main content -->
        <main class="flex-1 p-4 md:p-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">

                <div class="flex items-center space-x-4">
                    <!-- Hamburger toggle button for mobile -->
                    <button id="sidebarToggle" class="md:hidden p-2 text-white bg-blue-700 rounded hover:bg-blue-800">
                        ☰
                    </button>

                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">Voting Dashboard</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Welcome to Jitihada Voting System</p>
                    </div>
                </div>

                <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-medium shadow">
                    ● System Active
                </span>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 dark:text-gray-400">Total Members</h3>
                    <p id="total" class="text-4xl font-bold text-blue-600 dark:text-blue-400">0</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 dark:text-gray-400">Voted</h3>
                    <p id="voted" class="text-4xl font-bold text-green-600 dark:text-green-400">0</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 dark:text-gray-400">Pending</h3>
                    <p id="pending" class="text-4xl font-bold text-red-600 dark:text-red-400">0</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-lg transition">
                    <h3 class="text-gray-500 dark:text-gray-400">Turnout Rate</h3>
                    <p id="turnout" class="text-4xl font-bold text-purple-600 dark:text-purple-400">0%</p>
                </div>
            </div>

            <!-- Chart + Instructions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                <!-- Charts -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow lg:col-span-2 space-y-6">
                    <h2 class="font-semibold mb-3 text-gray-700 dark:text-gray-300">Live Voting Progress</h2>
                    <canvas id="chart" class="mb-6"></canvas>

                    <h2 class="font-semibold mb-3 text-gray-700 dark:text-gray-300">Voting Trends</h2>
                    <canvas id="trendChart"></canvas>
                </div>

                <!-- Instructions -->
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 text-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-3">How to Participate</h2>
                    <ul class="space-y-2 text-sm">
                        <li>✔ Register as a member</li>
                        <li>✔ Get REG.NO & assigned number</li>
                        <li>✔ Use REG.NO to vote</li>
                        <li>✔ Only one vote allowed</li>
                    </ul>

                    <div class="mt-5 space-y-2">
                        <a href="index.php"
                            class="block text-center bg-white text-blue-700 font-semibold py-2 rounded-lg hover:bg-gray-100">
                            Register Now
                        </a>
                        <a href="vote.php"
                            class="block text-center border border-white py-2 rounded-lg hover:bg-white hover:text-blue-700 transition">
                            Vote Here
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Voters -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow mb-6">
                <h2 class="font-semibold mb-3 text-gray-700 dark:text-gray-300">Recent Votes</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2">REG.NO</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Voted At</th>
                            </tr>
                        </thead>
                        <tbody id="recent-votes"></tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center text-xs text-gray-500 dark:text-gray-400 mt-10">
                © <?php echo date("Y"); ?> JITIHADA GROUP Voting System
                <br />
                Powered by <span class="font-semibold">Dantechdevs developers</span>
            </footer>
        </main>
    </div>

    <!-- Toast Notification -->
    <div id="toast"
        class="fixed top-5 right-5 hidden bg-green-500 text-white px-4 py-2 rounded shadow transition-opacity">
        ✅ New vote recorded!
    </div>

    <script>
    let chart, trendChart;
    let lastVoteCount = 0;

    // Fetch Stats
    async function loadStats() {
        const res = await fetch("api/fetch_members.php");
        const data = await res.json();

        document.getElementById("total").innerText = data.total;
        document.getElementById("voted").innerText = data.voted;
        document.getElementById("pending").innerText = data.pending;
        document.getElementById("turnout").innerText =
            data.total > 0 ? ((data.voted / data.total) * 100).toFixed(1) + "%" : "0%";

        // Toast notification for new votes
        if (data.voted > lastVoteCount) {
            showToast(`${data.voted - lastVoteCount} new vote(s) recorded!`);
            lastVoteCount = data.voted;
        }

        // Doughnut chart
        if (!chart) {
            chart = new Chart(document.getElementById('chart'), {
                type: 'doughnut',
                data: {
                    labels: ['Voted', 'Pending'],
                    datasets: [{
                        data: [data.voted, data.pending],
                        backgroundColor: ['#22c55e', '#ef4444'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: ${ctx.raw} members`
                            }
                        }
                    }
                }
            });
        } else {
            chart.data.datasets[0].data = [data.voted, data.pending];
            chart.update();
        }

        // Trend chart
        if (!trendChart) {
            trendChart = new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: data.dates || [],
                    datasets: [{
                        label: 'Votes Over Time',
                        data: data.votes_over_time || [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true
                }
            });
        } else {
            trendChart.data.labels = data.dates || [];
            trendChart.data.datasets[0].data = data.votes_over_time || [];
            trendChart.update();
        }
    }

    // Fetch Recent Votes
    async function loadRecentVotes() {
        const res = await fetch('api/fetch_recent_votes.php');
        const votes = await res.json();
        const tbody = document.getElementById('recent-votes');
        tbody.innerHTML = '';
        votes.forEach(v => {
            tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2">${v.reg_no}</td>
                        <td class="px-4 py-2">${v.full_name}</td>
                        <td class="px-4 py-2">${v.voted_at}</td>
                    </tr>`;
        });
    }

    // Toast function
    function showToast(msg) {
        const toast = document.getElementById('toast');
        toast.innerText = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // Dark mode toggle
    document.getElementById('darkToggle').addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
    });

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('active'); // Use active class for sliding
        sidebarOverlay.classList.toggle('hidden');
    }

    sidebarToggle.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', toggleSidebar);

    // Refresh data every 5s
    setInterval(() => {
        loadStats();
        loadRecentVotes();
    }, 5000);

    // Initial load
    loadStats();
    loadRecentVotes();
    </script>
</body>

</html>