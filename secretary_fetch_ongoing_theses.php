

<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die(json_encode(["error" => "Connection failed: " . mysqli_connect_error()]));
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    $user_id = $_SESSION['log_id'];



    $filtervalues = isset($_GET['searchthesis']) ? $_GET['searchthesis'] : '';
    $filtervalues = mysqli_real_escape_string($con, $filtervalues);

    $query = "
        SELECT the_id, the_title, the_status, the_date_assignation FROM thesis WHERE the_status = 'ongoing' OR the_status = 'under review';
    ";

    if ($filtervalues !== '') {
        $query .= " AND (t.the_status LIKE '%$filtervalues%' OR t.the_title LIKE '%$filtervalues%')";
    }

    $query_run = mysqli_query($con, $query);
    $thesisArray = [];

    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            $thesisArray[] = $row;
        }
        echo json_encode($thesisArray);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode(["error" => "User not logged in."]);
}

mysqli_close($con);
?>

