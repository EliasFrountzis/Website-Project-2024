<?php
session_start(); // Start the session to access session variables

$con = mysqli_connect("localhost", "root", "", "thesis_distributed");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the thesis ID is set in the URL
if (isset($_GET['id'])) {
    $thesis_id = $_GET['id'];

    // Query to get thesis details
    $query = "
        SELECT t.the_topic, t.the_date_assignation, t.the_status, 
               s.st_name, s.st_surname, 
               c.co_prof_1_id, c.co_prof_2_id, c.co_supervising_prof_id
        FROM thesis t 
        LEFT JOIN student s ON t.the_st_id = s.st_id
        LEFT JOIN committee c ON t.the_co_id = c.co_id 
        WHERE t.the_id = '$thesis_id'
    ";

    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $thesis_details = mysqli_fetch_assoc($query_run);
    } else {
        echo "<div>No Thesis Found</div>";
        exit;
    }
} else {
    echo "<div>No Thesis ID Provided</div>";
    exit;
}

// Get committee members' names
$committee_members = [];
$prof_ids = [$thesis_details['co_prof_1_id'], $thesis_details['co_prof_2_id'], $thesis_details['co_supervising_prof_id']];
foreach ($prof_ids as $prof_id) {
    if ($prof_id) {
        $prof_query = "SELECT prof_name, prof_surname FROM professor WHERE prof_id = '$prof_id'";
        $prof_result = mysqli_query($con, $prof_query);
        if ($prof_result && mysqli_num_rows($prof_result) > 0) {
            $prof_data = mysqli_fetch_assoc($prof_result);
            $committee_members[] = $prof_data['prof_name'] . ' ' . $prof_data['prof_surname'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="utf-8">
    <title>Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome_nosingin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
</head>
<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa fa-bars"></i>
        </label>
        <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">
       
        <ul>
            <li><a href="teacher_showthesissubjects.html">Thesis Subjects</a></li>
            <li><a href="teacherassingthesis.php">Assign Thesis to student</a></li>
            <li><a href="teacher_thesisList_3.php">Thesis List</a></li>
            <li><a href="teacher_answers_requests.php">Invites</a></li>
            <li><a href="teacher_statistics.html">Statistics</a></li>
            <li><a href="teacher_thesismanage.html">Thesis management</a></li>
        </ul>
    </nav>

   
    <div class="seethroughbox">
    <div class="show_subjects-box">
        <h2>Thesis Details</h2>
        <p><strong>Thesis Topic:</strong> <?=$thesis_details['the_topic']?></p>
        <p><strong>Student Name:</strong> <?=$thesis_details['st_name']?> <?=$thesis_details['st_surname']?></p>
        <p><strong>Date of Assignation:</strong> <?=$thesis_details['the_date_assignation']?></p>
        <p><strong>Status:</strong> <?=$thesis_details['the_status']?></p>
        <p><strong>Committee Members:</strong> <?=implode(', ', $committee_members)?></p>
    </div>
</div>
</body>
</html>