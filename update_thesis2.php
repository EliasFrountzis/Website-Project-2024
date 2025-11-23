<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$thesis_id = isset($_POST['thesis_id']) ? intval($_POST['thesis_id']) : 0;
$GSNumber = isset($_POST['GSNumber']) ? trim($_POST['GSNumber']) : '';
$GSYear = isset($_POST['GSYear']) ? trim($_POST['GSYear']) : '';
$cancelText = isset($_POST['cancelText']) ? trim($_POST['cancelText']) : '';

// Log the received data for debugging
error_log("Received data:");
error_log("thesis_id: $thesis_id");
error_log("GSNumber: $GSNumber");
error_log("GSYear: $GSYear");
error_log("cancelText: $cancelText");

if ($thesis_id) {
    // Retrieve student ID from the thesis table
    $query0 = "
        SELECT the_st_id 
        FROM thesis 
        WHERE the_id = ?
    ";
    $stmt0 = $con->prepare($query0);
    $stmt0->bind_param('i', $thesis_id);
    $stmt0->execute();
    $stmt0->bind_result($student_id);
    $stmt0->fetch();
    $stmt0->close();

    // Log the student ID
    error_log("Student ID for thesis $thesis_id: $student_id");

    if ($student_id) {
        // Insert into admin_documents table
        $query1 = "
            INSERT INTO admin_documents (admin_doc_cancel_GS_number, admin_doc_cancel_GS_year, admin_doc_cancel_thesis_text, admin_doc_st_id, admin_doc_admin_id) 
            VALUES (?, ?, ?, ?, ?)
        ";
        $admin_id = $_SESSION['admin_id'];  // Assuming you store the admin_id in the session
        $stmt1 = $con->prepare($query1);
        $stmt1->bind_param('sssii', $GSNumber, $GSYear, $cancelText, $student_id, $admin_id);
        $stmt1->execute();
        $stmt1->close();

        // Update thesis table
        $query2 = "
            UPDATE thesis 
            SET the_status = 'cancelled'
            WHERE the_id = ?
        ";
        $stmt2 = $con->prepare($query2);
        $stmt2->bind_param('i', $thesis_id);
        $stmt2->execute();
        $stmt2->close();

        echo json_encode(["success" => true]);
    } else {
        error_log("No student ID found for thesis $thesis_id");
        echo json_encode(["success" => false, "error" => "Student ID not found for the thesis."]);
    }
} else {
    error_log("No valid thesis ID received.");
    echo json_encode(["success" => false, "error" => "No valid thesis ID received."]);
}

mysqli_close($con);
?>
