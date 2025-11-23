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
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$thesis_id = isset($_GET['thesis_id']) ? intval($_GET['thesis_id']) : 0;

$query = "SELECT note_text FROM notes WHERE note_the_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $thesis_id);
$stmt->execute();
$result = $stmt->get_result();
$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

mysqli_close($con);
echo json_encode($notes);
?>
