<?php
session_start();

if (!isset($_SESSION['log_username'])) {
    die(json_encode(['success' => false, 'message' => 'You must be logged in to perform this action.']));
}

$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

// Get the thesis ID and presentation notice from the request
$thesis_id = $_POST['thesis_id'];
$presentation_notice = $_POST['presentation_notice'];

// Update the presentation notice in the thesis table
$query = "UPDATE thesis SET the_presentation_notice = ? WHERE the_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('si', $presentation_notice, $thesis_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Presentation notice updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating presentation notice.']);
}

$stmt->close();
mysqli_close($con);
?>