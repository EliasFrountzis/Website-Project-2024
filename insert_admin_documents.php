<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

$admin_id = isset($_SESSION['log_id']) ? intval($_SESSION['log_id']) : 0;
$thesis_id = isset($_POST['thesis_id']) ? intval($_POST['thesis_id']) : 0;
$GSNumber = isset($_POST['GSNumber']) ? trim($_POST['GSNumber']) : '';
$GSYear = isset($_POST['GSYear']) ? trim($_POST['GSYear']) : '';
$cancelText = isset($_POST['cancelText']) ? trim($_POST['cancelText']) : '';

// Log the received data for debugging
error_log("Admin ID: $admin_id, Thesis ID: $thesis_id, GS Number: $GSNumber, GS Year: $GSYear, Cancel Text: $cancelText");

if ($thesis_id && $GSNumber && $GSYear && $cancelText) {
    // Insert into admin_documents table
    $query = "
        INSERT INTO admin_documents (admin_doc_admin_id, admin_doc_cancel_GS_number, admin_doc_cancel_GS_year, admin_doc_cancel_thesis_text, admin_doc_st_id)
        VALUES (?, ?, ?, ?, (SELECT the_st_id FROM thesis WHERE the_id = ?))
    ";
    $stmt = $con->prepare($query);
    $stmt->bind_param('isssi', $admin_id, $GSNumber, $GSYear, $cancelText, $thesis_id);
    
    if ($stmt->execute()) {
        // Update thesis status to 'cancelled'
        $updateQuery = "
            UPDATE thesis 
            SET the_status = 'cancelled'
            WHERE the_id = ?
        ";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bind_param('i', $thesis_id);
        $updateStmt->execute();
        $updateStmt->close();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error inserting data into admin_documents."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Missing required fields."]);
}

mysqli_close($con);
?>
