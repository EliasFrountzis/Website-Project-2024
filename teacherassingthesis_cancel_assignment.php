<?php
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $the_id = $_POST['the_id'];

    // Update the thesis to cancel the assignment
    $query = "UPDATE thesis SET the_st_id = NULL, the_status = 'free' WHERE the_id = '$the_id'";
    if (mysqli_query($con, $query)) {
        header("Location: teacherassingthesis_ongoingassignments.php?success=1"); // Redirect back to ongoing assignments
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>