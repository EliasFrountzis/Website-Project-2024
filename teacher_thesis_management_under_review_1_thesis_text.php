<?php
session_start();

// Retrieve the thesis ID from the URL
$thesis_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($thesis_id <= 0) {
    die("Invalid Thesis ID.");
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <title>Thesis Text</title>
    <link rel="stylesheet" href="welcome_nosingin.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="teacher_thesis_management_under_review_1_thesis_text_script.js"></script>
</head>
<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa fa-bars"></i>
        </label>
        <img class="image" src="images/logo_oldmoney.png" alt="thesis old money logo">
       
        <ul>
            <li><a href="teacher_showthesissubjects.php">Thesis Subjects</a></li>
            <li><a href="teacherassingthesis.php">Assign Thesis to student</a></li>
            <li><a href="teacher_thesisList_3.php">Thesis List</a></li>
            <li><a href="teacher_answers_requests.php">Requests</a></li>
            <li><a href="teacher_statistics.html">Statistics</a></li>
			<li><a href="announcements.html">Announcements</a></li>
			<li><a href="logout.php">Logout</a></li>
        </ul>
        <br><br><br><br><br><br><br>
        <h2 class="form-title" style="text-align: center;">Thesis PDF</h2>
    </nav>

    <div id="thesis-content">
        <div id="pdfContainer"></div>
    </div>

    
</body>
</html>