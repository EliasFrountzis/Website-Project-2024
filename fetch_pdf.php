<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the student ID from the request
$st_id = $_GET['id']; // Get the student ID from the request

$query = "SELECT st_thesis_text FROM student WHERE st_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $st_id);
$stmt->execute();
$stmt->bind_result($pdf_data);

if ($stmt->fetch()) {
    // Check the size of the PDF data
    if (empty($pdf_data)) {
        die("No PDF data found for student ID: " . $st_id);
    }
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="thesis.pdf"');
    echo $pdf_data; // Output the PDF data
} else {
    echo "No PDF found.";
}

$stmt->close();
mysqli_close($con);
?>