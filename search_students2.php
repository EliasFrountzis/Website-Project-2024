<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
session_start();
$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['action']) && $_GET['action'] == 'search') {
    $searchTerm = mysqli_real_escape_string($con, $_GET['term']);
  
    $query = "SELECT s.* FROM student s LEFT JOIN thesis t ON s.st_id = t.the_st_id WHERE CONCAT(s.st_name, s.st_surname, s.st_id) LIKE '%$searchTerm%' AND t.the_st_id IS NULL";
       
    $result = mysqli_query($con, $query);

    $student = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $student[] = $row;
    }

    echo json_encode($student);
}
?>