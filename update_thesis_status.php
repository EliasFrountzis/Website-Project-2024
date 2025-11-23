<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$thesis_id = isset($_POST['thesis_id']) ? intval($_POST['thesis_id']) : 0;

// Log the received thesis ID for debugging
error_log("Received thesis ID: $thesis_id");

if ($thesis_id) {
    // Update thesis table
    $query = "
        UPDATE thesis 
        SET the_status = 'cancelled'
        WHERE the_id = ?
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $thesis_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["success" => true]);
} else {
    error_log("No valid thesis ID received.");
    echo json_encode(["success" => false, "error" => "No valid thesis ID received."]);
}

mysqli_close($con);
?>
