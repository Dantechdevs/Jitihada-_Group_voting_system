<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $count = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
    if ($count >= 10) {
        die("Registration closed. Max members reached.");
    }

    $number = $count + 1;
    $reg_no = "JTG-" . str_pad($number, 3, "0", STR_PAD_LEFT);

    $stmt = $pdo->prepare("INSERT INTO members(reg_no,full_name,phone,assigned_number)
                           VALUES(?,?,?,?)");
    $stmt->execute([$reg_no, $name, $phone, $number]);

    echo "Registered successfully. Your REG.NO: $reg_no , Number: $number";
}