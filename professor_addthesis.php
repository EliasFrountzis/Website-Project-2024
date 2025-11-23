<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// σύνδεση με βάση
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';

$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $the_topic = $_POST['the_topic'];
    $thesistitle = $_POST['thesistitle'];
    $thesisdescription = $_POST['thesisdescription'];
    $user_id = $_SESSION['user_id'];
    $current_date = date('Y-m-d'); // τωρινη ημερομηνία

    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) { //έλεγχος για το ανέβασμα του αρχείου
        $file = $_FILES['file']['tmp_name'];
        $fileType = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)); //παίρνει την επέκταση του αρχείου και τα μετατρέπει σε μικρά
        if ($fileType != 'pdf') {
            die('Only PDF files are allowed.');
        }
        $fileContent = file_get_contents($file); //διάβασμα αρχείου

        $stmt = $con->prepare("INSERT INTO thesis (the_topic, the_title, the_description, the_description_file, the_supervising_prof_id, the_date_assignation) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $the_topic, $thesistitle, $thesisdescription, $fileContent, $user_id, $current_date);
        $stmt->send_long_data(3, $fileContent);  // συνάρτηση για πέρασμα των Blob δεδομένων
    } else {
        $stmt = $con->prepare("INSERT INTO thesis (the_topic, the_title, the_description, the_supervising_prof_id, the_date_assignation) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $the_topic, $thesistitle, $thesisdescription, $user_id, $current_date);
    }

    if ($stmt->execute()) {
        header('Location: professorshowthesis.php');
        echo 'New record created successfully';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>
