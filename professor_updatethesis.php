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
    die('Connection failed: ' . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $the_id = $_POST['the_id'];
    $the_topic = $_POST['the_topic'];
    $the_title = $_POST['the_title'];
    $the_description = $_POST['the_description'];
    $user_id = $_SESSION['user_id'];

    //έλεγχος αν ανέβηκε αρχείο
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file']['tmp_name'];
        $fileType = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if ($fileType != 'pdf') {
            die('Only PDF files are allowed.');
        }
        $fileContent = file_get_contents($file);

        $stmt = $con->prepare("UPDATE thesis SET the_topic = ?, the_title = ?, the_description = ?, the_description_file = ? WHERE the_id = ? AND the_supervising_prof_id = ?");
        $stmt->bind_param("sssbii", $the_topic, $the_title, $the_description, $fileContent, $the_id, $user_id);
        $stmt->send_long_data(3, $fileContent);  // συνάρτηση για πέρασμα των Blob δεδομένων
    } else {
        $stmt = $con->prepare("UPDATE thesis SET the_topic = ?, the_title = ?, the_description = ? WHERE the_id = ? AND the_supervising_prof_id = ?");
        $stmt->bind_param("sssii", $the_topic, $the_title, $the_description, $the_id, $user_id);
    }

    if ($stmt->execute()) {
        header('Location: professorshowthesis.php');
        echo 'Record updated successfully';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
}




$con->close();
?>
