<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['log_username'])) {
    die(json_encode(['success' => false, 'message' => 'You must be logged in to view this page.']));
}

$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

// Retrieve the thesis ID from the URL
$thesis_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($thesis_id <= 0) {
    die(json_encode(['success' => false, 'message' => 'Invalid Thesis ID.']));
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

// Get the committee ID for the thesis
$co_id_query = "SELECT the_co_id FROM thesis WHERE the_id = ?";
if ($stmt = $con->prepare($co_id_query)) {
    $stmt->bind_param('i', $thesis_id);
    $stmt->execute();
    $stmt->bind_result($the_co_id);
    
    if (!$stmt->fetch()) {
        die(json_encode(['success' => false, 'message' => 'No committee found for this thesis.']));
    }
    $stmt->close();
} else {
    die(json_encode(['success' => false, 'message' => 'Error preparing committee ID query.']));
}

// Check the status of co_the_exampination
$status_query = "SELECT co_the_examination FROM committee WHERE co_id = ? AND co_supervising_prof_id = ?";
if ($stmt = $con->prepare($status_query)) {
    $stmt->bind_param('ii', $the_co_id, $professor_id);
    $stmt->execute();
    $stmt->bind_result($co_the_exampination);
    
    if (!$stmt->fetch()) {
        die(json_encode(['success' => false, 'message' => 'No examination status found for this committee.']));
    }
    $stmt->close();
} else {
    die(json_encode(['success' => false, 'message' => 'Error preparing status query: ' . mysqli_error($con)]));
}

mysqli_close($con);

// Return the examination status as JSON
echo json_encode(['success' => true, 'co_the_exampination' => $co_the_exampination]);
?>
