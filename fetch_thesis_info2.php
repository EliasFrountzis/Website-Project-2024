<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$student_id = isset($_SESSION['log_id']) ? intval($_SESSION['log_id']) : 0;

$query = "
    SELECT t.the_id, t.the_title, t.the_status, t.the_presentation_notice, t.the_grade, 
           t.the_date_completion, p.prof_name, p.prof_surname, s.st_name, s.st_surname, s.st_id
    FROM thesis t
    LEFT JOIN professor p ON t.the_supervising_prof_id = p.prof_id
    LEFT JOIN student s ON t.the_st_id = s.st_id
    WHERE t.the_st_id = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$stmt->bind_result($thesis_id, $thesis_title, $thesis_status, $presentation_notice, $grade, 
                   $date_completion, $prof_name, $prof_surname, $st_name, $st_surname, $st_id);
$stmt->fetch();
$result = [
    'thesis_id' => $thesis_id,
    'thesis_title' => $thesis_title,
    'thesis_status' => $thesis_status,
    'presentation_notice' => $presentation_notice,
    'thesis_grade' => $grade,
    'the_date_completion' => $date_completion,
    'supervising_prof_name' => $prof_name,
    'supervising_prof_surname' => $prof_surname,
    'student_name' => $st_name,
    'student_surname' => $st_surname,
    'student_id' => $st_id
];

$stmt->close();
echo json_encode($result);

mysqli_close($con);
?>
