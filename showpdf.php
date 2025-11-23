<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    exit('Please log in first.');
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    // ανάκτηση αρχείου
    $sql = "SELECT the_description_file FROM thesis WHERE the_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $file_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($file_data);
    $stmt->fetch();

    if ($file_data) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="thesisdescription.pdf"');
        echo $file_data;
    } else {
        echo 'File not found.';
    }
}
?>
