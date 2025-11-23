<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';

$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

$thesis_title = $_POST['thesis-title'];

$sql0 = "SELECT the_st_id FROM thesis WHERE the_title = ?";
$stmt0 = $con->prepare($sql0);
$stmt0->bind_param("s", $thesis_title);
$stmt0->execute();
$stmt0->bind_result($student_id);
$stmt0->fetch();
$stmt0->close();

$sql1 = "UPDATE thesis_assignment SET the_ass_cancellation_reason = 'from professor', the_ass_cancellation = 'Active' WHERE the_ass_st_id = ?";
$stmt1 = $con->prepare($sql1);
$stmt1->bind_param("i", $student_id);
$stmt1->execute();

$sql3 = "UPDATE thesis SET the_status = 'cancelled' WHERE the_title = ?";
$stmt3 = $con->prepare($sql3);
$stmt3->bind_param("s", $thesis_title);
$stmt3->execute();


$con->close();



?>

