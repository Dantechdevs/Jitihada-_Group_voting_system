<?php
require '../config/db.php';

$total = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
$voted = $pdo->query("SELECT COUNT(*) FROM members WHERE has_voted=1")->fetchColumn();

echo json_encode([
    "total" => $total,
    "voted" => $voted,
    "pending" => $total - $voted
]);