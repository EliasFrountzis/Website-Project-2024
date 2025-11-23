<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$thesis_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Get the student ID associated with the thesis
$student_id = 0;
$query = "
    SELECT the_st_id FROM thesis WHERE the_id = ?
";
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $thesis_id);
    $stmt->execute();
    $stmt->bind_result($student_id);
    $stmt->fetch();
    $stmt->close();
}

// Update the status to 'free' and reset the student ID
$query = "
    UPDATE thesis SET the_status = 'free', the_st_id = NULL WHERE the_id = ?
";
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $thesis_id);
    $stmt->execute();
    $stmt->close();
}

// Delete the requests made by the student
$query = "
    DELETE FROM request WHERE req_st_id = ?
";
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(["success" => true]);

mysqli_close($con);
?>
