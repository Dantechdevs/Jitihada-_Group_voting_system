<?php
require '../config/db.php';

$name = $_POST['name'];
$phone = $_POST['phone'];

$count = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
if ($count >= 10) {
    exit("Registration closed. Max 10 members reached.");
}

$next = $pdo->query("SELECT IFNULL(MAX(lucky_number),0)+1 FROM members")->fetchColumn();
$reg_no = "JTG" . str_pad($next, 3, "0", STR_PAD_LEFT);

$stmt = $pdo->prepare("INSERT INTO members (reg_no, name, phone, lucky_number) VALUES (?,?,?,?)");
$stmt->execute([$reg_no, $name, $phone, $next]);

echo "Registered! Your number is $next and REG.NO is $reg_no";