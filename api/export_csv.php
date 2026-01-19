<?php
require_once "../config/db.php";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=members.csv");

$out = fopen("php://output", "w");
fputcsv($out, ["REG.NO", "NAME", "PHONE", "NUMBER", "VOTED"]);

$stmt = $pdo->query("SELECT * FROM members");
while ($row = $stmt->fetch()) {
    fputcsv($out, [
        $row['reg_no'],
        $row['full_name'],
        $row['phone'],
        $row['assigned_number'],
        $row['has_voted'] ? 'Yes' : 'No'
    ]);
}
fclose($out);