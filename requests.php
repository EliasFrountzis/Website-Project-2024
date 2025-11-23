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

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['req_id'])) {
    $req_id = mysqli_real_escape_string($con, $_POST['req_id']);
    $action = mysqli_real_escape_string($con, $_POST['action']);
    
    if ($action === 'accept') {
        $update_query = "UPDATE request SET req_status = 'accepted', req_acceptance_date = NOW() WHERE req_id = '$req_id' AND req_prof_id = '$professor_id'";
        if (mysqli_query($con, $update_query)) {
            // Check if the student has exactly 2 accepted requests
            $student_query = "SELECT req_st_id FROM request WHERE req_id = ?";
            if ($stmt = $con->prepare($student_query)) {
                $stmt->bind_param('i', $req_id);
                $stmt->execute();
                $stmt->bind_result($student_id);
                $stmt->fetch();
                $stmt->close();
                
                // Now count the accepted requests for this student by different professors
                $count_query = "SELECT COUNT(DISTINCT req_prof_id) FROM request WHERE req_st_id = ? AND req_status = 'accepted'";
                if ($count_stmt = $con->prepare($count_query)) {
                    $count_stmt->bind_param('i', $student_id);
                    $count_stmt->execute();
                    $count_stmt->bind_result($accepted_professor_count);
                    $count_stmt->fetch();
                    $count_stmt->close();

                    // If the count is exactly 2, delete all undetermined requests for this student
                    if ($accepted_professor_count == 2) {
                        $delete_query = "DELETE FROM request WHERE req_st_id = ? AND req_status = 'undetermined'";
                        if ($delete_stmt = $con->prepare($delete_query)) {
                            $delete_stmt->bind_param('i', $student_id);
                            $delete_stmt->execute();
                            $delete_stmt->close();
                        }
                    }
                }
            }
            echo json_encode(['success' => true, 'message' => 'Request accepted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating request.']);
        }
    } elseif ($action === 'decline') {
        $update_query = "UPDATE request SET req_status = 'rejected', req_rejection_date = NOW() WHERE req_id = '$req_id' AND req_prof_id = '$professor_id'";
        if (mysqli_query($con, $update_query)) {
            echo json_encode(['success' => true, 'message' => 'Request declined successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating request.']);
        }
    }
} else {
    // Fetch requests for the logged-in professor
    $query = "SELECT r.*, s.st_name, s.st_surname 
              FROM request r 
              JOIN student s ON r.req_st_id = s.st_id 
              WHERE r.req_status = 'undetermined' AND r.req_prof_id = ?";

    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('i', $professor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        $stmt->close();

        echo json_encode(['success' => true, 'requests' => $requests]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement.']);
    }
}

mysqli_close($con);
?>