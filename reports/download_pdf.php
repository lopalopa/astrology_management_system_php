<?php
// Start session and check admin authentication
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../config/db.php';

// Include TCPDF library for PDF generation
require_once '../libs/tcpdf_min/tcpdf.php';

// Include PhpSpreadsheet for Excel export
require_once '../libs/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Get report type and format from query parameters
$report_type = $_GET['type'] ?? 'appointments'; // Default to appointments report
$format = $_GET['format'] ?? 'pdf';              // Default to PDF

// Fetch report data from database based on report_type
function getReportData($conn, $report_type) {
    if ($report_type == 'users') {
        $sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
    } elseif ($report_type == 'appointments') {
        $sql = "SELECT a.id, u.name AS user_name, ast.name AS astrologer_name, a.appointment_date, a.status
                FROM appointments a
                JOIN users u ON a.user_id = u.id
                JOIN astrologers ast ON a.astrologer_id = ast.id
                ORDER BY a.appointment_date DESC";
    } else {
        // Add other report types if needed
        $sql = "";
    }
    $result = $conn->query($sql);
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

$data = getReportData($conn, $report_type);

if ($format === 'pdf') {
    // Generate PDF report
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Astrology Management System');
    $pdf->SetTitle(ucfirst($report_type) . ' Report');
    $pdf->AddPage();

    $html = "<h2>" . ucfirst($report_type) . " Report</h2><table border=\"1\" cellpadding=\"4\"><thead><tr>";

    // Header row depends on report type
    if ($report_type == 'users') {
        $html .= "<th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created At</th>";
    } elseif ($report_type == 'appointments') {
        $html .= "<th>ID</th><th>User Name</th><th>Astrologer Name</th><th>Appointment Date</th><th>Status</th>";
    }
    $html .= "</tr></thead><tbody>";

    foreach ($data as $row) {
        $html .= "<tr>";
        foreach ($row as $cell) {
            $html .= "<td>" . htmlspecialchars($cell) . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";

    $pdf->writeHTML($html);
    $pdf->Output(strtolower($report_type) . '_report.pdf', 'D'); // Download PDF

} elseif ($format === 'excel') {
    // Generate Excel report
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header row
    if ($report_type == 'users') {
        $headers = ['ID', 'Name', 'Email', 'Role', 'Created At'];
    } elseif ($report_type == 'appointments') {
        $headers = ['ID', 'User Name', 'Astrologer Name', 'Appointment Date', 'Status'];
    } else {
        $headers = [];
    }

    $sheet->fromArray($headers, NULL, 'A1');

    // Data rows
    $rowNum = 2;
    foreach ($data as $row) {
        $sheet->fromArray(array_values($row), NULL, 'A' . $rowNum);
        $rowNum++;
    }

    // Output Excel file to browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . strtolower($report_type) . '_report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else {
    echo "Unsupported format requested.";
}
?>