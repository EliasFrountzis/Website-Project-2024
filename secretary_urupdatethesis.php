<?php

// σύνδεση με τη βάση
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);


if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


$thesis_id = $_POST['thesis_id'];

$sql = "UPDATE thesis SET the_status = 'finished' WHERE the_id = $thesis_id";

if ($con->query($sql) === TRUE) {
    echo "Thesis status updated successfully.";
} else {
    echo "Error updating thesis status: " . $con->error;
}

$con->close();
?>
