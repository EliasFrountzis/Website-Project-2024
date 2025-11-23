<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    $user_id = $_SESSION['log_id'];

    // Check if the session variable is set
    if (isset($_SESSION['log_username'])) {
        $log_prof_email_id = trim($_SESSION['log_username']);
    } else {
        echo "Professor email not found in session.<br>";
        $log_prof_email_id = ''; // Set a default value or handle the error
    }

    // Get the professor's ID
    $prof_query = "SELECT prof_id FROM professor WHERE prof_email = ?";
    if ($stmt = $con->prepare($prof_query)) {
        $stmt->bind_param('s', $log_prof_email_id);
        $stmt->execute();
        $stmt->bind_result($prof_id);
        $stmt->fetch();
        $stmt->close();
            // Now fetch theses supervised by the professor
            if (isset($prof_query)) {
                $thesis_query = "SELECT the_id, the_title FROM thesis WHERE the_supervising_prof_id = ?";
                if ($stmt = $con->prepare($thesis_query)) {
                    $stmt->bind_param('i', $prof_id);
                    $stmt->execute();
                    $thesis_result = $stmt->get_result();

                    $thesisOptions = [];
                    while ($thesis = $thesis_result->fetch_assoc()) {
                        $thesisOptions[] = $thesis;
                    }

                    echo json_encode($thesisOptions);
                
            } else {
                // If no professor ID is found, return an empty array
                echo json_encode([]);
            }
        }
    } else {
        // If student_id is not set, return an empty array
        echo json_encode([]);
    }}
else {
    // If the user is not logged in, return an empty array
    echo json_encode([]);
}

?>