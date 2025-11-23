<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$professor_id = isset($_POST['professor_id']) ? intval($_POST['professor_id']) : 0;
$student_email = isset($_SESSION['log_username']) ? trim($_SESSION['log_username']) : '';

// Log professor ID for debugging
error_log("Professor ID: $professor_id");

// Fetch student ID based on email
$query = "
    SELECT st_id 
    FROM student 
    WHERE st_email = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param('s', $student_email);
$stmt->execute();
$stmt->bind_result($student_id);
$stmt->fetch();
$stmt->close();

// Log student ID for debugging
error_log("Student ID: $student_id");

// Verify professor ID exists
$query = "
    SELECT COUNT(*) as professor_count 
    FROM professor 
    WHERE prof_id = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $professor_id);
$stmt->execute();
$stmt->bind_result($professor_count);
$stmt->fetch();
$stmt->close();

// Log professor count for debugging
error_log("Professor Count: $professor_count");

if ($professor_count == 0) {
    die(json_encode(["error" => "Invalid professor ID."]));
}

// Insert the new supervision request with the current date
$current_date = date('Y-m-d');
$query = "
    INSERT INTO request (req_request_date, req_status, req_way, req_st_id, req_st_email_id, req_prof_id) 
    VALUES (?, 'undetermined', 'oral', ?, ?, ?)
";
$stmt = $con->prepare($query);
$stmt->bind_param('sisi', $current_date, $student_id, $student_email, $professor_id);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true]);

// Check if two professors have agreed to supervision
$query = "
    SELECT COUNT(*) as accepted_count 
    FROM request 
    WHERE req_st_id = ? AND req_status = 'accepted'
";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$stmt->bind_result($accepted_count);
$stmt->fetch();
$stmt->close();

if ($accepted_count >= 2) {
    // Update the thesis status to 'ongoing' and cancel other requests
    $query = "
        UPDATE thesis 
        SET the_status = 'ongoing' 
        WHERE the_st_id = ?
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $stmt->close();

    $query = "
        DELETE FROM request 
        WHERE req_st_id = ? AND req_status != 'accepted'
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $stmt->close();
}

mysqli_close($con);
?>
