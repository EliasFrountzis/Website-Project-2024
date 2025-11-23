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
$student_email = $_SESSION['log_username'];

// Fetch the professor's ID based on the email
$student_query = "SELECT st_id FROM student WHERE st_email = ?";
if ($stmt = $con->prepare($student_query)) {
    $stmt->bind_param('s', $student_email);
    $stmt->execute();
    $stmt->bind_result($student_id);
    
    if (!$stmt->fetch()) {
        die(json_encode(['success' => false, 'message' => 'No student found with this email.']));
    }
    $stmt->close();
} else {
    die(json_encode(['success' => false, 'message' => 'Error preparing statement.']));
}

$supervising_prof_id = null;

$query = " SELECT the_supervising_prof_id FROM thesis WHERE the_st_id = ?
";
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $stmt->bind_result($supervising_prof_id);
    $stmt->fetch();
    $stmt->close();
}

$searchTerm = isset($_GET['searchprofessor']) ? mysqli_real_escape_string($con, $_GET['searchprofessor']) : '';

$query = " SELECT prof_id, prof_name, prof_surname, prof_email FROM professor WHERE CONCAT(prof_name, ' ', prof_surname) LIKE '%$searchTerm%' AND prof_id != ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $supervising_prof_id);
$stmt->execute();
$result = $stmt->get_result();

$professors = [];
while ($row = $result->fetch_assoc()) {
    $professors[] = $row;
}

echo json_encode($professors);

$stmt->close();
mysqli_close($con);
?>
