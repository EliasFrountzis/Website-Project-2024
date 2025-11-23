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

$query = " SELECT t.the_id, t.the_title, t.the_status, t.the_supervising_prof_id, p.prof_name, p.prof_surname, t.the_date_completion, t.the_grade, t.the_presentation_notice
    FROM thesis t
    LEFT JOIN professor p ON t.the_supervising_prof_id = p.prof_id
    WHERE t.the_st_id = ?
";
$result = [];
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $stmt->bind_result($thesis_id, $thesis_title, $thesis_status, $supervising_prof_id, $prof_name, $prof_surname, $the_date_completion, $the_grade, $the_presentation_notice);
    $stmt->fetch();
    $result = [
        'thesis_id' => $thesis_id,
        'thesis_title' => $thesis_title,
        'thesis_status' => $thesis_status,
        'supervising_prof_id' => $supervising_prof_id,
        'supervising_prof_name' => $prof_name,
        'supervising_prof_surname' => $prof_surname,
        'the_date_completion' => $the_date_completion,
        'the_grade' => $the_grade,
        'the_presentation_notice' => $the_presentation_notice
    ];
    $stmt->close();
}

echo json_encode($result);

mysqli_close($con);
?>
