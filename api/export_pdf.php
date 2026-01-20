<?php
require_once "../config/db.php";
require_once "../TCPDF-main/tcpdf.php"; // Adjust path to your TCPDF

// Fetch all members
$stmt = $pdo->query("SELECT full_name, reg_no, assigned_number, has_voted FROM members ORDER BY assigned_number ASC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create new PDF
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('JITIHADA GROUP Voting System');
$pdf->SetTitle('Voting Results');
$pdf->SetHeaderData('', 0, 'Voting Results', 'JITIHADA GROUP');
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->setFooterFont(['helvetica', '', 10]);
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetMargins(15, 30, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(15);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Voting Results', 0, 1, 'C');
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(10, 8, '#', 1, 0, 'C', 1);
$pdf->Cell(60, 8, 'Full Name', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'REG.NO', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Assigned #', 1, 0, 'C', 1);
$pdf->Cell(30, 8, 'Status', 1, 1, 'C', 1);

// Table data
$pdf->SetFont('helvetica', '', 11);
foreach ($members as $index => $m) {
    $status = $m['has_voted'] ? 'Voted' : 'Pending';
    $pdf->Cell(10, 8, $index + 1, 1, 0, 'C');
    $pdf->Cell(60, 8, $m['full_name'], 1, 0, 'L');
    $pdf->Cell(30, 8, $m['reg_no'], 1, 0, 'C');
    $pdf->Cell(30, 8, $m['assigned_number'] ?? '-', 1, 0, 'C');
    $pdf->Cell(30, 8, $status, 1, 1, 'C');
}

// Output PDF
$pdf->Output('Voting_Results.pdf', 'D'); // 'D' forces download