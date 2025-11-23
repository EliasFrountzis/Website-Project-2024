<?php
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the student ID and the thesis ID from the form submission
    $student_id = $_POST['student_id'];
    $thesis_id = $_POST['the_id'];

    // Prepare the SQL statement to update the thesis
    $query = "UPDATE thesis SET the_st_id = ?, the_status = 'under assignment' WHERE the_id = ?";
    $stmt = mysqli_prepare($con, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'ii', $student_id, $thesis_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect back to the original page with a success message
        header("Location: teacherassingthesis.php?success=1"); // Change to your original page
        exit();
    } else {
        echo "Error assigning thesis: " . mysqli_error($con);
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>