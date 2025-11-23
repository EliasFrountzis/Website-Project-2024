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

$query1 = "
    INSERT INTO admin_documents (admin_doc_cancel_GS_number, admin_doc_cancel_GS_year, admin_doc_cancel_thesis_text, admin_doc_st_id, admin_doc_admin_id) 
    VALUES (?, ?, ?, (SELECT the_st_id FROM thesis WHERE the_id = ?), ?)
";
$admin_id = $_SESSION['admin_id'];  // Assuming you store the admin_id in the session
$stmt1 = $con->prepare($query1);
$stmt1->bind_param('sssii', $GSNumber, $GSYear, $cancelText, $thesis_id, $admin_id);
$stmt1->execute();
$stmt1->close();

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

mysqli_close($con);
?>
