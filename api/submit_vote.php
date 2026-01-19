<?php
require_once "../config/db.php";

$reg = $_POST['reg_no'];
$number = $_POST['number'];

$check = $pdo->prepare("SELECT has_voted FROM members WHERE reg_no=?");
$check->execute([$reg]);
$row = $check->fetch();

if (!$row) {
    die("Invalid REG.NO");
}

if ($row['has_voted']) {
    die("You already voted");
}

$pdo->prepare("INSERT INTO votes(reg_no,voted_number) VALUES(?,?)")
    ->execute([$reg, $number]);

$pdo->prepare("UPDATE members SET has_voted=1 WHERE reg_no=?")
    ->execute([$reg]);

echo "Vote submitted successfully";