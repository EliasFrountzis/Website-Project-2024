<?php
session_start();
header('Content-Type: application/json');
$con = new mysqli("localhost", "root", "", "thesis_distributed");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($con->connect_error) {
    error_log("Connection failed: " . $con->connect_error);
    die(json_encode(["error" => "Connection failed: " . $con->connect_error]));
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    $gsNumber = isset($_POST['gs_number']) ? $_POST['gs_number'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
    $thesisTitle = isset($_POST['thesis_title']) ? $_POST['thesis_title'] : '';

    if ($gsNumber && $year && $reason && $thesisTitle) {
        $sql0 = "SELECT the_st_id FROM thesis WHERE the_title = ?";
        $stmt0 = $con->prepare($sql0);
        if ($stmt0 === false) {
            error_log("Error preparing statement: " . $con->error);
            die(json_encode(['error' => 'Error preparing statement: ' . $con->error]));
        }
        $stmt0->bind_param("s", $thesisTitle);
        $stmt0->execute();
        $stmt0->bind_result($student_id);
        $stmt0->fetch();
        $stmt0->close();

        if (!$student_id) {
            die(json_encode(['error' => 'No student ID found for the thesis']));
        }

       
        $sql_check = "SELECT * FROM thesis_assignment WHERE the_ass_st_id = ?";
        $stmt_check = $con->prepare($sql_check);
        if ($stmt_check === false) {
            error_log("Error preparing statement: " . $con->error);
            die(json_encode(['error' => 'Error preparing statement: ' . $con->error]));
        }
        $stmt_check->bind_param("i", $student_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            die(json_encode(['error' => 'No matching student ID found in thesis_assignment']));
        }
        $stmt_check->close();

        $sql1 = "UPDATE thesis_assignment SET the_ass_cancellation_reason = 'from admin', the_ass_cancellation = 'Active' WHERE the_ass_st_id = ?";
        $stmt1 = $con->prepare($sql1);
        if ($stmt1 === false) {
            error_log("Error preparing statement: " . $con->error);
            die(json_encode(['error' => 'Error preparing statement: ' . $con->error]));
        }
        $stmt1->bind_param("i", $student_id);
        $stmt1->execute();

        if ($stmt1->affected_rows === 0) {
            die(json_encode(['error' => 'No rows updated in thesis_assignment']));
        }
        $stmt1->close();

        $sql2 = "UPDATE thesis SET the_status = 'cancelled' WHERE the_title = ?";
        $stmt2 = $con->prepare($sql2);
        if ($stmt2 === false) {
            error_log("Error preparing statement: " . $con->error);
            die(json_encode(['error' => 'Error preparing statement: ' . $con->error]));
        }
        $stmt2->bind_param('s', $thesisTitle);
        $stmt2->execute();
        $stmt2->close();

        $sql3 = "UPDATE admin_documents SET admin_doc_cancel_GS_number = ?, admin_doc_cancel_GS_year = ?, admin_doc_cancel_thesis_text = ? WHERE admin_doc_st_id = ?";
        $stmt3 = $con->prepare($sql3);
        if ($stmt3 === false) {
            error_log("Error preparing statement: " . $con->error);
            die(json_encode(['error' => 'Error preparing statement: ' . $con->error]));
        }
        $stmt3->bind_param("issi", $gsNumber, $year, $reason, $student_id);

        if ($stmt3->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            die(json_encode(['error' => 'Error updating admin_documents: ' . $stmt3->error]));
        }
        $stmt3->close();
    } else {
        echo json_encode(["error" => "Invalid input data."]);
    }
} else {
    echo json_encode(["error" => "User not logged in."]);
}

mysqli_close($con); 
?>
