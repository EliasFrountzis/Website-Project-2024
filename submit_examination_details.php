<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$student_id = isset($_SESSION['log_id']) ? intval($_SESSION['log_id']) : 0;
$date_time = isset($_POST['dateTime']) ? $_POST['dateTime'] : '';
$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
$venue = isset($_POST['venue']) ? $_POST['venue'] : '';

// Update thesis examination details
$query = "
    UPDATE thesis 
    SET the_presentation_notice = ?, the_date_completion = ?
    WHERE the_st_id = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param('ssi', $venue, $date_time, $student_id);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true]);

mysqli_close($con);
?>
