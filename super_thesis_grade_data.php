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

// Fetch average thesis grades data
$query = "SELECT AVG(the_grade) AS average_grade, the_title 
          FROM thesis 
          WHERE the_status = 'finished' AND the_supervising_prof_id = ?
          GROUP BY the_title"; // Group by title to get average for each thesis
          
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $professor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $titles = [];
    $average_grades = [];
    while ($row = $result->fetch_assoc()) {
        $titles[] = $row['the_title']; // Store thesis titles
        $average_grades[] = round($row['average_grade'], 2); // Store average grades rounded to 2 decimal places
    }
    $stmt->close();

    // Prepare data for the chart
    echo json_encode(['success' => true, 'labels' => $titles, 'values' => $average_grades]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing statement.']);
}

mysqli_close($con);
?>