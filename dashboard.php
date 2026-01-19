<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-900 text-white p-5">

    <h2>JITIHADA GROUP Dashboard</h2>

    <canvas id="chart"></canvas>

    <script>
    let chart;

    async function loadStats() {
        const res = await fetch("api/fetch_stats.php");
        const data = await res.json();

        if (!chart) {
            chart = new Chart(document.getElementById('chart'), {
                type: 'pie',
                data: {
                    labels: ['Voted', 'Pending'],
                    datasets: [{
                        data: [data.voted, data.pending]
                    }]
                }
            });
        } else {
            chart.data.datasets[0].data = [data.voted, data.pending];
            chart.update();
        }
    }

    setInterval(loadStats, 5000);
    loadStats();
    </script>
</body>

</html>