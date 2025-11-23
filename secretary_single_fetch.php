<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    $thesisId = isset($_GET['id']) ? $_GET['id'] : '';


    if ($thesisId !== '') {
        $query = "SELECT the_id, the_title, the_status, the_date_assignation FROM thesis WHERE the_id = '$thesisId'";
        $exec = mysqli_query($con, $query);

        if (mysqli_num_rows($exec) > 0) {
            $thesis = mysqli_fetch_assoc($exec);
            echo json_encode($thesis);
        } else {
            error_log("Thesis not found."); 
            echo json_encode(["error" => "Thesis not found."]);
        }
    } 
} else {
    echo json_encode(["error" => "User not logged in."]);
}

mysqli_close($con);

?>
