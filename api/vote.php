<?php
require '../config/db.php';

$reg = $_POST['reg_no'];

$check = $pdo->prepare("SELECT has_voted FROM members WHERE reg_no=?");
$check->execute([$reg]);
$user = $check->fetch();

if (!$user) exit("Invalid REG.NO");
if ($user['has_voted']) exit("Already voted!");

$pdo->prepare("UPDATE members SET has_voted=1 WHERE reg_no=?")->execute([$reg]);
$pdo->prepare("INSERT INTO votes (reg_no) VALUES (?)")->execute([$reg]);

echo "Vote successful!";