<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    exit('Please log in first.');
}


$user_id = $_SESSION['user_id'];

//σύνδεση με βάση
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'thesis_distributed';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);


if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


$sqlSelect = "
    SELECT thesis.the_id, thesis.the_topic, thesis.the_title, thesis.the_description, thesis.the_description_file, thesis.the_status, thesis.the_supervising_prof_id, thesis.the_date_assignation,
           p3.prof_name AS sprof_name, p3.prof_surname AS sprof_surname,
           p1.prof_name AS prof1_name, p1.prof_surname AS prof1_surname, 
           p2.prof_name AS prof2_name, p2.prof_surname AS prof2_surname
    FROM thesis 
    JOIN committee ON thesis.the_co_id = committee.co_id
    JOIN professor p3 ON committee.co_supervising_prof_id = p3.prof_id
    JOIN professor p1 ON committee.co_prof_1_id = p1.prof_id
    JOIN professor p2 ON committee.co_prof_2_id = p2.prof_id
    WHERE thesis.the_st_id = '$user_id'
";
$result = mysqli_query($con, $sqlSelect);


if ($result) {
    while ($data = mysqli_fetch_array($result)) {
       
        $date_assignation = $data['the_date_assignation'];
        $date_assignation_obj = new DateTime($date_assignation);
        $current_date_obj = new DateTime();
        $interval = $current_date_obj->diff($date_assignation_obj);
        $time_since_assignment = $interval->y . ' years, ' . $interval->m . ' months, ' . $interval->d . ' days';

        echo 'Topic: ' . htmlspecialchars($data["the_topic"]) . '<br>';
        echo 'Title: ' . htmlspecialchars($data["the_title"]) . '<br>';
        echo 'Description: ' . htmlspecialchars($data["the_description"]) . '<br>';
        echo 'Pdf File: <a href="showpdf.php?id=' . $data["the_id"] . '" target="_blank">View/Download</a><br>';
        echo 'Status: ' . htmlspecialchars($data["the_status"]) . '<br>';
        echo 'Supervisor Name: ' . htmlspecialchars($data["sprof_name"]) . ' ' . htmlspecialchars($data["sprof_surname"]) . '<br>';
        echo 'Professor 1: ' . htmlspecialchars($data["prof1_name"]) . ' ' . htmlspecialchars($data["prof1_surname"]) . '<br>';
        echo 'Professor 2: ' . htmlspecialchars($data["prof2_name"]) . ' ' . htmlspecialchars($data["prof2_surname"]) . '<br>';
        echo 'Time Since Assignment: ' . htmlspecialchars($time_since_assignment) . '<br><br>';
    }
} else {
    echo "Error: " . mysqli_error($con);
}




?>
