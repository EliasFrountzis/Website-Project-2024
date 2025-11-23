<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$thesis_id = isset($_POST['thesis_id']) ? intval($_POST['thesis_id']) : 0;

// Log the received data
error_log("Received data: thesis_id = $thesis_id");

$query = "
    SELECT st_thesis_url 
    FROM student_thesis_urls 
    WHERE st_thesis_url IS NOT NULL 
    AND st_url_st_id = (SELECT the_st_id FROM thesis WHERE the_id = ?)
";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $thesis_id);
$stmt->execute();
$stmt->bind_result($thesis_url);
$stmt->fetch();

if ($thesis_url) {
    error_log("Supporting URL found: $thesis_url");
    $query = "
        UPDATE thesis 
        SET the_status = 'finished'
        WHERE the_id = ?
    ";
    $stmt2 = $con->prepare($query);
    $stmt2->bind_param('i', $thesis_id);
    $stmt2->execute();
    $stmt2->close();

    echo json_encode(["success" => true]);
} else {
    error_log("Supporting URL not found");
    echo json_encode(["error" => "Supporting URL not found."]);
}

$stmt->close();
mysqli_close($con);
?>
