<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$thesis_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$log_prof_email_id = isset($_SESSION['log_username']) ? trim($_SESSION['log_username']) : '';

// Fetch the logged-in professor's ID
$professor_id = 0;
$prof_query = "SELECT prof_id FROM professor WHERE prof_email = ?";
if ($stmt = $con->prepare($prof_query)) {
    $stmt->bind_param('s', $log_prof_email_id);
    $stmt->execute();
    $stmt->bind_result($professor_id);
    $stmt->fetch();
    $stmt->close();
}

$query = "
    SELECT t.the_id, t.the_title, s.st_id, s.st_name, s.st_surname, r.req_prof_id, r.req_status, t.the_supervising_prof_id
    FROM thesis t
    LEFT JOIN student s ON t.the_st_id = s.st_id
    LEFT JOIN request r ON s.st_id = r.req_st_id
    WHERE t.the_id = ?
";
$requests = [];
$is_supervising_prof = false;
if ($stmt = $con->prepare($query)) {
    $stmt->bind_param('i', $thesis_id);
    $stmt->execute();
    $stmt->bind_result($thesis_id, $thesis_title, $student_id, $student_name, $student_surname, $req_prof_id, $req_status, $supervising_prof_id);
    while ($stmt->fetch()) {
        $requests[] = [
            'student_id' => $student_id,
            'student_name' => $student_name,
            'student_surname' => $student_surname,
            'req_prof_id' => $req_prof_id,
            'req_status' => $req_status
        ];
        if ($supervising_prof_id == $professor_id) {
            $is_supervising_prof = true;
        }
    }
    $stmt->close();
}

$result = [
    'thesis_id' => $thesis_id,
    'thesis_title' => $thesis_title,
    'requests' => $requests,
    'is_supervising_prof' => $is_supervising_prof
];

echo json_encode($result);

mysqli_close($con);
?>
