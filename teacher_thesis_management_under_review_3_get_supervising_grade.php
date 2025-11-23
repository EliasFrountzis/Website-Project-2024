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

// Step 1: Get the committee ID (the_co_id) for the thesis
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

// Step 2: Fetch grades for the committee using the_co_id
$committee_query = "SELECT c.co_id, c.co_prof_1_grade, c.co_prof_2_grade, c.co_supervising_prof_grade, c.co_supervising_prof_id, t.the_title 
                    FROM committee c 
                    JOIN thesis t ON t.the_co_id = c.co_id
                    WHERE t.the_status = 'under review' 
                    AND c.co_id = ?
                    AND c.co_supervising_prof_id = ?";
if ($stmt = $con->prepare($committee_query)) {
    $stmt->bind_param('ii', $the_co_id, $professor_id);
    $stmt->execute();
    $stmt->bind_result($co_id, $co_prof_1_grade, $co_prof_2_grade, $co_supervising_prof_grade, $co_supervising_prof_id, $the_title);
    
    $grades = [];
    while ($stmt->fetch()) {
        $grades[] = [
            'co_id' => $co_id,
            'co_prof_1_grade' => $co_prof_1_grade,
            'co_prof_2_grade' => $co_prof_2_grade,
            'co_supervising_prof_grade' => $co_supervising_prof_grade,
            'co_supervising_prof_id' => $co_supervising_prof_id,
            'the_title' => $the_title,
            'is_co_supervising_prof' => ($professor_id == $co_supervising_prof_id)
        ];
    }
    
    if (empty($grades)) {
        die(json_encode(['success' => false, 'message' => 'Professor is not in a committee.']));
    }
    
    $stmt->close();
} else {
    die(json_encode(['success' => false, 'message' => 'Error preparing committee query.']));
}

mysqli_close($con);

// Return the grades as JSON
$response = ['success' => true, 'grades' => $grades];
echo json_encode($response);
error_log("Response: " . json_encode($response)); // Log the response

?>