<?php
require_once "../config/db.php";

header('Content-Type: application/json');

// Total members
$total = $pdo->query("SELECT COUNT(*) as total FROM members")->fetchColumn();

// Voted
$voted = $pdo->query("SELECT COUNT(*) as voted FROM members WHERE voted = 1")->fetchColumn();

// Pending
$pending = $total - $voted;

// Votes over time (grouped by date)
$stmt = $pdo->query("SELECT DATE(voted_at) as date, COUNT(*) as votes 
                     FROM members 
                     WHERE voted = 1 
                     GROUP BY DATE(voted_at) 
                     ORDER BY DATE(voted_at)");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dates = [];
$votes = [];
foreach ($rows as $row) {
    $dates[] = $row['date'];
    $votes[] = $row['votes'];
}

echo json_encode([
    'total' => (int)$total,
    'voted' => (int)$voted,
    'pending' => (int)$pending,
    'dates' => $dates,
    'votes_over_time' => $votes
]);