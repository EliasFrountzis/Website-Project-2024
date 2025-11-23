<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$student_id = isset($_SESSION['log_id']) ? intval($_SESSION['log_id']) : 0;
$url = isset($_POST['url']) ? trim($_POST['url']) : '';

// Insert URL into database
$query = "
    INSERT INTO student_thesis_urls (st_url_st_id, st_thesis_url) 
    VALUES (?, ?)
";
$stmt = $con->prepare($query);
$stmt->bind_param('is', $student_id, $url);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true]);

mysqli_close($con);
?>
