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
$query = "SELECT the_title, DATEDIFF(the_date_completion, the_date_assignation) AS duration 
          FROM thesis 
          WHERE the_status = 'finished' AND the_supervising_prof_id = ?";
          
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $professor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $titles = [];
    $durations = [];
    while ($row = $result->fetch_assoc()) {
        $titles[] = $row['the_title']; // Store thesis titles
        $durations[] = $row['duration'];
    }
    $stmt->close();

    // Calculate average duration
    if (count($durations) > 0) {
        $average_duration = array_sum($durations) / count($durations);
        $average_duration = round($average_duration, 2); // Round to 2 decimal places
    } else {
        $average_duration = 0;
    }

    // Prepare data for the chart
    $labels = ['Average Duration'];
    $values = [$average_duration];

    echo json_encode(['success' => true, 'labels' => $titles, 'values' => $durations]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing statement.']);
}

mysqli_close($con);
?>
