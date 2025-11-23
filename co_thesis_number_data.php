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
    $stmt->fetch();
    $stmt->close();
} else {
    die(json_encode(['success' => false, 'message' => 'Error preparing statement.']));
}

// Fetch finished theses data
$query = "SELECT the_title 
          FROM thesis t
          JOIN committee c ON t.the_co_id = c.co_id
          WHERE the_status = 'finished'
          AND (c.co_prof_1_id = ? OR c.co_prof_2_id = ?)";
          
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('ii', $professor_id, $professor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $titles = [];
    $total_theses = 0;

    while ($row = $result->fetch_assoc()) {
        $titles[] = $row['the_title']; // Store thesis titles
        $total_theses++; // Increment the total count for each thesis
    }

    echo json_encode(['success' => true, 'labels' => $titles, 'values' => array_fill(0, count($titles), $total_theses)]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing statement.']);
}

mysqli_close($con);
?>