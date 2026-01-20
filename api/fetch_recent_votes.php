<?php
require_once "../config/db.php";

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT reg_no, name as full_name, voted_at
                     FROM members
                     WHERE voted = 1
                     ORDER BY voted_at DESC
                     LIMIT 10");

$votes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($votes);