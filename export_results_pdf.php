<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die("Unauthorized access");
}

require 'config/db.php';
require 'TCPDF-main/tcpdf.php';

// Fetch data
$stmt = $pdo->query("SELECT name, reg_no, lucky_number, has_voted FROM members ORDER BY lucky_number ASC");
$rows = $stmt->fetchAll();

// Create PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("JITIHADA GROUP Voting Results");
$pdf->AddPage();

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'JITIHADA GROUP - Voting Results', 0, 1, 'C');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(10, 8, '#', 1);
$pdf->Cell(50, 8, 'Name', 1);
$pdf->Cell(40, 8, 'REG.NO', 1);
$pdf->Cell(30, 8, 'Number', 1);
$pdf->Cell(30, 8, 'Voted', 1);
$pdf->Ln();

// Table Data
$pdf->SetFont('helvetica', '', 10);
$count = 1;

foreach ($rows as $row) {
    $pdf->Cell(10, 8, $count++, 1);
    $pdf->Cell(50, 8, $row['name'], 1);
    $pdf->Cell(40, 8, $row['reg_no'], 1);
    $pdf->Cell(30, 8, $row['lucky_number'], 1);
    $pdf->Cell(30, 8, $row['has_voted'] ? 'Yes' : 'No', 1);
    $pdf->Ln();
}

// Output
$pdf->Output('jitihada_results.pdf', 'D');