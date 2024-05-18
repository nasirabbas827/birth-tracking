<?php
require('./fpdf/fpdf.php'); // Adjust the path to where you saved FPDF

session_start();
include('config.php');

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the birth record ID from the URL parameter
if (!isset($_GET['birthRecordId']) || empty($_GET['birthRecordId'])) {
    die("No birth record ID provided.");
}

$birthRecordId = $_GET['birthRecordId'];

// Fetch the birth record from the database
$sql = "SELECT * FROM BirthRecords WHERE BirthRecordID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $birthRecordId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$record = mysqli_fetch_assoc($result);

if (!$record) {
    die("No record found with the provided ID.");
}

// Fetch district name
$district_query = "SELECT DistrictName FROM districts WHERE DistrictID = " . $record['DistrictID'];
$district_result = mysqli_query($conn, $district_query);
$district_row = mysqli_fetch_assoc($district_result);
$district_name = $district_row['DistrictName'];

// Fetch tehsil name
$tehsil_query = "SELECT TehsilName FROM tehsils WHERE TehsilID = " . $record['TehsilID'];
$tehsil_result = mysqli_query($conn, $tehsil_query);
$tehsil_row = mysqli_fetch_assoc($tehsil_result);
$tehsil_name = $tehsil_row['TehsilName'];

// Fetch union council name
$union_query = "SELECT UnionCouncilName FROM unioncouncils WHERE UnionCouncilID = " . $record['UnionCouncilID'];
$union_result = mysqli_query($conn, $union_query);
$union_row = mysqli_fetch_assoc($union_result);
$union_name = $union_row['UnionCouncilName'];

// Create a new PDF document
$pdf = new FPDF();
$pdf->AddPage();

// Set document title and add logo
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(0, 51, 102); // Dark blue
$pdf->Cell(0, 10, 'Birth Certificate', 0, 1, 'C');
$pdf->Ln(10);

// Draw a border around the certificate
$pdf->SetLineWidth(1);
$pdf->SetDrawColor(0, 51, 102); // Dark blue
$pdf->Rect(10, 20, 190, 250);

// Add subtitle
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0); // Black
$pdf->Cell(0, 10, 'Certificate of Birth Registration', 0, 1, 'C');
$pdf->Ln(10);

// Add record details with improved formatting and colors
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(224, 235, 255); // Light blue background

$pdf->Cell(50, 10, 'Child Name:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $record['ChildName'], 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Father Name:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $record['FatherName'], 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Mother Name:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $record['MotherName'], 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Birth Date:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $record['BirthDate'], 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Gender:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $record['Gender'], 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Mother NIC:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $record['MotherNIC'], 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Father NIC:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $record['FatherNIC'], 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'District:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $district_name, 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Tehsil:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $tehsil_name, 0, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Union Council:', 0, 0, 'L', true);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $union_name, 0, 1, 'L', true);

// Add footer with a signature line
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, 'Signature of Registrar', 0, 1, 'C');
$pdf->Ln(5);
$pdf->Cell(0, 10, '_____________________________', 0, 1, 'C');

// Output the PDF
$pdf->Output();
?>
