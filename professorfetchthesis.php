<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';

$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ($con->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $con->connect_error]));
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT the_id, the_topic, the_title, the_description, the_date_assignation FROM thesis WHERE the_supervising_prof_id = ?";
$stmt = $con->prepare($sql);

if ($stmt === false) {
    die(json_encode(['error' => 'Prepare failed: ' . $con->error]));
}
//πέρασμα id 
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
//αρχικοποίηση άδειου πίνακα για πέρασμα αποτελεσμάτων
$theses = array();
while ($row = $result->fetch_assoc()) {
    //προσθήκη των αποτελεσμάτων στο πίνακα
    $theses[] = $row;
}

$stmt->close();
$con->close();

echo json_encode($theses);
?>
