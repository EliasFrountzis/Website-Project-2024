<?php
session_start();

if (!isset($_SESSION['log_username'])) {
    die(json_encode(['success' => false, 'message' => 'You must be logged in to view this page.']));
}

$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

// Get the logged-in professor's email
$professor_email = $_SESSION['log_username'];

// Fetch the professor's ID based on the email
$professor_query = "SELECT prof_id FROM professor WHERE prof_email = ?";
if ($stmt = $con->prepare($professor_query)) {
    $stmt->bind_param('s', $professor_email);
    $stmt->execute();
    $stmt->bind_result($professor_id);
    
    if (!$stmt->fetch()) {
        die(json_encode(['success' => false, 'message' => 'No professor found with this email.']));
    }
    $stmt->close();
} else {
    die(json_encode(['success' => false, 'message' => 'Error preparing statement.']));
}

// Get the data from the POST request
$co_id = isset($_POST['co_id']) ? intval($_POST['co_id']) : 0;
$grade = isset($_POST['grade']) ? intval($_POST['grade']) : 0;
$gradeField = isset($_POST['gradeField']) ? $_POST['gradeField'] : '';

// Validate inputs
if ($co_id <= 0 || $grade < 0) {
    die(json_encode(['success' => false, 'message' => 'Invalid input data.']));
}

// Update the grade in the database
$update_query = "UPDATE committee SET $gradeField = ? WHERE co_id = ? AND co_supervising_prof_id = ?";
if ($stmt = $con->prepare($update_query)) {
    $stmt->bind_param('iii', $grade, $co_id, $professor_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Grade updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating grade.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing update statement.']);
}

mysqli_close($con);
?>