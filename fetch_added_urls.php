<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$student_id = isset($_SESSION['log_id']) ? intval($_SESSION['log_id']) : 0;

$query = "
    SELECT st_thesis_url 
    FROM student_thesis_urls 
    WHERE st_url_st_id = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$urls = [];

while ($row = $result->fetch_assoc()) {
    $urls[] = [
        'url' => $row['st_thesis_url']
    ];
}

$stmt->close();
echo json_encode($urls);

mysqli_close($con);
?>
