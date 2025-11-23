<?php

header('Content-Type: application/json');


session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';

// δημιουργία σύνδεσης
$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);



// έλεγχος σύνδεσης 
if ($con->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $con->connect_error]);
    exit;
}

// πέρασμα POST data
$number = $_POST['number'];
$text = $_POST['text'];
$thesis_title = $_POST['thesis_title']; 



// ενημέρωση πίνακα admin_documents 
$sql1 = "UPDATE admin_documents SET admin_doc_AP = ?, admin_practical_exam = ? WHERE admin_doc_st_id = (SELECT the_st_id FROM thesis WHERE the_title = ?)";
$stmt1 = $con->prepare($sql1);
if ($stmt1 === false) {
    error_log('Error preparing statement for admin_documents update: ' . $con->error);
    echo json_encode(['error' => 'Error preparing statement: ' . $con->error]);
    exit;
}
$stmt1->bind_param("sss", $number, $text, $thesis_title);
$stmt1->execute();



if ($stmt1->affected_rows === 0) {
    error_log("No rows updated in admin_documents for thesis title: " . $thesis_title);
    echo json_encode(['error' => 'No rows updated in admin_documents']);
    exit;
}

error_log("Rows updated in admin_documents: " . $stmt1->affected_rows);

echo json_encode(['status' => 'success']);

$con->close();
?>

