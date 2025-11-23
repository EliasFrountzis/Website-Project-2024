<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['log_username'])) {
    die(json_encode(['success' => false, 'message' => 'You must be logged in to view this page.']));
}

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

// Retrieve the thesis ID from the URL
$thesis_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($thesis_id <= 0) {
    die(json_encode(['success' => false, 'message' => 'Invalid Thesis ID.']));
}

// Prepare and execute the query to fetch the student ID
$thesis_query = "SELECT the_st_id FROM thesis WHERE the_id = ?";
if ($stmt = $con->prepare($thesis_query)) {
    $stmt->bind_param('i', $thesis_id);
    $stmt->execute();
    $stmt->bind_result($st_id);
    
    if ($stmt->fetch()) {
        // Return the student ID as a JSON response
        echo json_encode(['success' => true, 'st_id' => $st_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No thesis found with this ID.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing thesis query.']);
}

mysqli_close($con);
?>