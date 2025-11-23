<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$student_id = isset($_SESSION['log_id']) ? intval($_SESSION['log_id']) : 0;
$query = "
    SELECT st_thesis_supporting_material 
    FROM student_thesis_supporting_material 
    WHERE st_sup_mat_st_id = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$files = [];

while ($row = $result->fetch_assoc()) {
    $files[] = [
        'file_path' => $row['st_thesis_supporting_material'],
        'file_name' => basename($row['st_thesis_supporting_material'])
    ];
}

$stmt->close();
echo json_encode($files);

mysqli_close($con);
?>
