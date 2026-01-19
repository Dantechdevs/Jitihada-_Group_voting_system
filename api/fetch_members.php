<?php
require_once "../config/db.php";

header('Content-Type: application/json');

$data = $pdo->query("SELECT reg_no, full_name, phone, assigned_number, has_voted FROM members ORDER BY assigned_number ASC")->fetchAll(PDO::FETCH_ASSOC);

// Count totals
$total = count($data);
$voted = count(array_filter($data, fn($m) => $m['has_voted']));
$pending = $total - $voted;

echo json_encode([
    'total' => $total,
    'voted' => $voted,
    'pending' => $pending,
    'members' => $data
]);