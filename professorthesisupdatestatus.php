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

if (isset($_POST['thesis_id'])) {
    $thesis_id = $_POST['thesis_id'];

    $query = "UPDATE thesis SET the_status = 'under review' WHERE the_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $thesis_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'Thesis status updated successfully!';
    } else {
        echo 'Failed to update thesis status.';
    }
}

mysqli_close($con);
?>


