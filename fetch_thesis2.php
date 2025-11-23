<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    $user_id = $_SESSION['log_id'];

    if (isset($_SESSION['log_username'])) {
        $log_prof_email_id = trim($_SESSION['log_username']);
    } else {
        echo json_encode(["error" => "Professor email not found in session."]);
        exit;
    }

    $prof_query = "SELECT prof_id FROM professor WHERE prof_email = ?";
    if ($stmt = $con->prepare($prof_query)) {
        $stmt->bind_param('s', $log_prof_email_id);
        $stmt->execute();
        $stmt->bind_result($professor_id);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo json_encode(["error" => "Failed to prepare professor query."]);
        exit;
    }

    if (!isset($professor_id)) {
        echo json_encode(["error" => "No professor found with that email.", "professor_id" => null]);
        exit;
    }

    $filtervalues = isset($_GET['searchthesis']) ? $_GET['searchthesis'] : '';
    $filtervalues = mysqli_real_escape_string($con, $filtervalues);

    $query = "
        SELECT t.*, c.co_prof_1_id, c.co_prof_2_id 
        FROM thesis t 
        LEFT JOIN committee c ON t.the_co_id = c.co_id 
        WHERE (c.co_prof_1_id = '$professor_id' OR c.co_prof_2_id = '$professor_id' OR c.co_supervising_prof_id = '$professor_id')
    ";

    if ($filtervalues !== '') {
        $query .= " AND (t.the_status LIKE '%$filtervalues%' OR t.the_title LIKE '%$filtervalues%')";
    }

    $query_run = mysqli_query($con, $query);
    $thesisArray = [];

    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            $thesisArray[] = $row;
        }
        echo json_encode($thesisArray);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode(["error" => "User not logged in."]);
}

mysqli_close($con);
?>
