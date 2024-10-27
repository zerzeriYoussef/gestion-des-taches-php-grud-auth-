<?php
// Load the database configuration file
require_once '../connectToDatabase.php';

// Include XLSX generator library
require_once 'PhpXlsxGenerator.php';

// Excel file name for download
$fileName = "todolist_" . date('Y-m-d') . ".xlsx";

// Define column names
$excelData[] = array('ID', 'Text', 'User ID', 'Status');

// Fetch records from the todolist table and store in an array
$query = $database->prepare("SELECT * FROM todolist");
$query->execute();
while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $status = ($row['status'] == 'yes') ? 'منجز' : 'غير منجز';
    $lineData = array($row['id'], $row['text'], $row['userId'], $status);
    $excelData[] = $lineData;
}

// Export data to Excel and download as XLSX file
$xlsx =PhpXlsxGenerator::fromArray($excelData);
$xlsx->downloadAs($fileName);
if (isset($_POST['export'])) {
    generateAndDownloadExcel();
}
exit;
?>
